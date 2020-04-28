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

class AvatarLook extends Model
{
    protected $table = 'avatar_look';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dwCharacterID', 'nGender', 'nSkin', 'nFace',
        'nHair', 'nJob', 'nWeaponStickerID', 'nWeaponID',
        'nSubWeaponID', 'bDrawElfEar', 'nXenonDefFaceAcc',
        'nDemonSlayerDefFaceAcc', 'nBeastDefFaceAcc',
        'nBeastEars', 'nBeastTail', 'nMixedHairColor',
        'nMixedHairPercent', 'nPetPrimary', 'nPetSecondary',
        'nPetTertiary', 'anEquip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];	
}
