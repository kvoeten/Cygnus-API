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

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AvatarData extends Model
{
    protected $table = 'avatar_data';
    protected $primaryKey = 'dwCharacterID';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dwCharacterID','nAccountID', 'nWorld', 'nCharListPos', 'nRank',
        'nRankMove', 'nOverallRank', 'nOverallRankMove'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];	

    /**
     * Child data fetchable through matching primary key
     */
    public function AvatarLook()
    {
        return $this->hasOne('App\AvatarLook', 'dwCharacterID');
    }

    /**
     * Child data fetchable through matching primary key
     */
    public function CharacterStat()
    {
        return $this->hasOne('App\CharacterStat', 'dwCharacterID');
    }

    /**
     * Child data fetchable through matching primary key
     */
    public function DressUpInfo()
    {
        return $this->hasOne('App\DressUpInfo', 'dwCharacterID');
    }

    /**
     * Child data fetchable through matching primary key
     */
    public function WildHunterInfo()
    {
        return $this->hasOne('App\WildHunterInfo', 'dwCharacterID');
    }

    /**
     * Child data fetchable through matching primary key
     */
    public function ZeroInfo()
    {
        return $this->hasOne('App\ZeroInfo', 'dwCharacterID');
    }
}
