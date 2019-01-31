<?php 
namespace App\Http\Controllers;

use App\User;
use App\Activation;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class JoinController extends Controller {
	
	public function __construct() {
		//Can add auth middleware here i guess
	}
	
	public function store(Request $request) {
		$this->validateRequest($request);
		$password = $request->get('password');
		$email = $request->get('email');
		
		$client = new Client();
    
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            ['form_params'=>
                [
                    'secret'=> env('CAPTCHA_SECRET', null),
                    'response'=>$request->get('g-recaptcha-response')
                 ]
            ]
        );
    
        $body = json_decode((string)$response->getBody());
		
		if (!$body->success) {
			return $this->error("Improper Captcha Response.", 200);
		}
		
		if (strlen($request->get('name')) < 3) {
			return $this->error("The provided name is too short. (At least three characters required.)", 200);
		}
		
		if (strlen($request->get('name')) > 40) {
			return $this->error("The provided name is too long. (At max. fourty characters are allowed.)", 200);
		}
		
		if (User::where('email', '=', $email)->exists()) {
		    return $this->error("The provided email adress is already registered.", 200);
		}
		
		if (User::where('name', '=', $request->get('name'))->exists()) {
		    return $this->error("That name is already taken.", 200);
		}
		
		if ($password != $request->get('password_confirmation')) {
			return $this->error("The provided passwords don't match.", 200);
		}
			
		$user = User::create([
					'name' => $request->get('name'),
					'email' => $email,
					'password' => Hash::make($password),
				]);
		
		$token = str_random(60);
		$activation = Activation::create([
				'email' => $email,
				'activation_key' => $token
			]);
		
		Mail::send('emails.email', ['title_image' => "welcome.png", 'content' => "The Cygnus teams welcomes you! We only ask you to verify your email before continuing.", 'button_url' => 'https://api.maplecygnus.com/verify?token='.$token, 'button_text' => 'Verify Account!'], function ($message) use ($email)
        {
            $message->from('accounts@maplecygnus.com', 'Maple Cygnus');
            $message->to($email);
			$message->subject('Verify your Cygnus account.');
        });
			
		if ($user) {
			return $this->success($user, 200);
		} else {
			return $this->error("An unknown error occured.", 404);	
		}
	}
	
	public function validateRequest(Request $request) {
		$rules = [
			'name' => 'required', 
			'email' => 'required',
			'password' => 'required',
			'password_confirmation' => 'required',
			'g-recaptcha-response' => 'required'
		];
		$this->validate($request, $rules);
	}
}