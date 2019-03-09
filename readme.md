# Cygnus API

The Cygnus API is a RESTful Lumen API aimed at providing a multitude of endpoints for MapleStory private servers.

It's main features are:

  - User/ Account management.
  - WZ data enpoints.
  - E-mail account verifitcaion.
  - News, Ranking, Login, Account creation and other website features. 
  - OAuth2 aimed at enabling SSO (Compatible and tested with most forum software).
  
The API is compatible with a (slightly modified) version of Aria by Alan Morel (https://github.com/AlanMorel/aria)
A fork of Aria that has been made compatible with the API can be found at: https://github.com/kvoeten/aria
Please note that when the API gets updated it may take a few days for the aria fork to be updated to match the changes.

As this API supports OAuth2 it can be used for NXL-Alike (token) logins on MapleStory private servers. 
Tokens expire in 30 minutes.

# Installation (very rough and poorly explained):

1. Excecute the SQL file on (preferable) a MariaDB sql server.
2. Put all files on a PHP 7+ compatible linux web host (some features won't work on windows).
3. Edit .env.example to your liking.
4. Rename .env.example to .env and enjoy the API.
5. For using WZ data please dump as JSON using: https://github.com/kvoeten/Harepacker-resurrected

# Overview of API links

| Method | Endpoint | Function | Parameters | Requires OAuth2 Token | Access Level |
| ------ | ------ | ------ | ------ | ------ | ------ |
| GET | /join | Creates a new user. | name, email, passowrd, password_confirmation, month, day, year, gender,g-recaptcha-response | No | 0+ |
| GET | /verify | Verifies a new user's email. | token | No | 0+ || GET | /ranking | Shows the top 5 ranking characters. | None | No | 0+ |
| GET | /avatar/{name} | Shows an image of the avatar with provided name. | None | No | 0+ |
| GET | /server | Shows the current server info/ status. | None | No | 0+ |
| GET | /image/{name} | Shows an image asset. | None | No | 0+ |
| GET | /news/all | Shows all news posts. | None | No | 0+ |
| GET | /news | Shows the five latest news posts. | None | No | 0+ |
| GET | /news/{page_id} | Shows 5 posts per page if the page contains any posts. | None | No | 0+ |
| GET | /post/{news_id} | Shows the contents of a news post of the gives ID. | None | No | 0+ |
| GET | /news/all | Shows all news posts. | None | No | 0+ |
| GET | /map/{id} | Shows wz map data for the given ID. | None | No | 0+ |
| GET | /map/image/{id} | Shows wz map image for the given ID. | None | No | 0+ |
| GET | /npc/{id} | Shows wz npc data for the given ID. | None | No | 0+ |
| GET | /item/npc/{id} | Shows wz npc image for the given ID. | None | No | 0+ |
| GET | /mob/{id} | Shows wz mob data for the given ID. | None | No | 0+ |
| GET | /mob/image/{id} | Shows wz mob image for the given ID. | None | No | 0+ |
| GET | /item/{id} | Shows wz item data for the given ID. | None | No | 0+ |
| GET | /item/image/{id} | Shows wz item image for the given ID. | None | No | 0+ |
| GET | /search | Search for a WZ data entry by name and type. | name, type | No | 0+ |
| GET | /user | Shows user info. | None | Yes | 0+ |
| GET | /account | Shows cygnus game account info. | None | Yes | 0+ |
| POST | /post | Create a news post. | title, content, author | Yes | 2+ |
| PUT | /post/{news_id} | Update a news post. | title, content, author | Yes | 2+ |
| DELETE | /post/{news_id} | Delete a news post. | None. | Yes | 2+ |
| POST | /ranking | Truncates ranking and inserts latest info. * | None. | Yes | 5+ |

* Ranking truncation is done based upon a provided JSON body in the request containing the latest information.
This system is still subject to change as it would create issues when the ranking table gets too large.

# Overview of TODO API links currently planned

| Method | Endpoint | Function | Parameters | Requires OAuth2 Token | Access Level |
| ------ | ------ | ------ | ------ | ------ | ------ |
| POST | /account | Edits a Cygnus game account. | Undefined. | Yes | 2+ |
| POST | /ban/{user_id} | Bans an user. | Undefined. | Yes | 3+ |
| POST | /server | Edits a Cygnus game account. | Undefined. | Yes | 5+ |
| GET | /blocklist | Gets list of banned/blocked IP's/Users. | Undefined. | Yes | 5+ |

# TODO
1. Better ranking updates.
2. Ban/ Blocklost functionality.
3. Editing user information (password resets)

Please don't hesitate to create pull requests or report any issues/ security risks. I shall do my best to incorporate any feedback.

