<?php 
/*
	This file is a part of the Cygnus API, a RESTful Lumen based API.
    Copyright (C) 2018 Kaz Voeten

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use Gate;

class Controller extends BaseController {
	
    /**
     * Return a JSON response for success.
     *
     * @param  array  $data
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function success($data, $code) {
		return response()->json(['success' => true, 'data' => $data], $code);
	}
	
    /**
     * Return a JSON response for error.
     *
     * @param  array  $message
     * @param  string $code
     * @return \Illuminate\Http\JsonResponse
     */
	public function error($message, $code) {
		return response()->json(['success' => false, 'error' => $message], $code);
	}
	
    /**
     * Check if the user is authorized to perform a given action on a resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array $resource
     * @param  mixed|array $arguments
     * @return boolean
     * @see    https://lumen.laravel.com/docs/authorization 
     */
    protected function authorizeUser(Request $request, $resource, $arguments = []) {
    	
    	$user 	 = User::find($this->getUserId());
    	$action	 = $this->getAction($request); 
		
        // The ability string must match the string defined in App\Providers\AuthServiceProvider\ability()
        $ability = "{$action}-{$resource}";
		
    	// return $this->authorizeForUser($user, "{$action}-{$resource}", $data);
    	return Gate::forUser($user)->allows($ability, $arguments);
    }
	
    /**
     * Check if user is authorized.
     *
     * This method will be called by "Authorize" Middleware for every controller.
     * Controller that needs to be authorized must override this method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function isAuthorized(Request $request) {
        return false;
    }
	
    /**
     * Get current authorized user id.
     * This method should be called only after validating the access token using OAuthMiddleware Middleware.
     *
     * @return boolean
     */
    protected function getUserId() {
    	//return \LucaDegasperi\OAuth2Server\Facades\Authorizer::getResourceOwnerId();
    }
	
    /**
     * Get the requested action method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getAction(Request $request) {
        return explode('@', $request->route()[1]["uses"], 2)[1];
    }
	
    /**
     * Get the parameters in route.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getArgs(Request $request) {
        return $request->route()[2];
    }
}