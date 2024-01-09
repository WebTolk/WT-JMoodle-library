[![Version](https://img.shields.io/badge/Version-1.0.0-blue.svg)]() [![Status](https://img.shields.io/badge/Status-stable-green.svg)]() [![JoomlaVersion](https://img.shields.io/badge/Joomla-4.x-orange.svg)]() [![JoomlaVersion](https://img.shields.io/badge/Joomla-5.x-important.svg)]() [![Version](https://img.shields.io/badge/PHP-8.x-blue.svg)]()
[![Version](https://img.shields.io/badge/Joomla_Extensions_Directory-red.svg)](https://extensions.joomla.org/extension/core-enhancements/coding-a-scripts-integration/wt-jmoodle-library/)

# WT JMoodle library
Native Joomla 4 / Joomla 5 PHP library for working with the Moodle REST API.
## Joomla System plugin for Moodle library settings
![image](https://github.com/WebTolk/WT-JMoodle-library/assets/6236403/5f794e15-a71e-4306-b8d0-00afc6dc495a)

# How to use Moodle connetction library in Joomla
Video instruction and a little demo (in Russian).
[![Youtube video instruction in Russian](https://img.youtube.com/vi/mEmSFFb3rTo/0.jpg)](https://www.youtube.com/watch?v=mEmSFFb3rTo)

## Install and configure Moodle and Joomla
- Install a JMoodle library in Joomla
- Go to your Moodle and create token for Joomla
- Set this token to JMoodle library in system plugin settings

## How to create a Moodle web services token for Joomla 
To get a token, follow these steps:
- Create a special user in Moodle, on whose behalf Joomla will act in Moodle and access the REST API methods. `Administration / Users / Accounts / Add User`. Do not appoint this user as the site administrator.
- Create a role for a special Moodle user and assign her the necessary access rights in `Administration / Users / Rights / Define roles`. **The presence or absence of accesses in this section (role context and rights) will affect the work with some REST API methods**. The access rights required for the methods are visible at the stage of adding functions for the web service.
- Create a `External service` in `Administration / Server / Web Services / External Services`.
- After creating an external service from the list of external services, go to the `functions` of the created service and add the REST API methods necessary for the integration to work. Add the `core_webservice_get_site_info` method in order to see in Joomla that the integration really works, as well as a list of methods available for the REST API.
- Create a `token` in `Administration / Server / Web Services / Tokens` for a specially created user from under whom Joomla will access the REST API.

If you have done everything correctly, you will see a list of available Moodle REST API methods in your Joomla

![image](https://github.com/WebTolk/WT-JMoodle-library/assets/6236403/e41cef88-6e38-4a44-abce-f7d4439dcf0a)
# How to make a request to Moodle webservices from Joomla via REST API?

```php
use Webtolk\JMoodle\JMoodle;

$moodle = new JMoodle();

/**
 * Request method. 
 * 
 * @param   string  $method  Moodle REST API method
 * @param   array   $data    data for Moodle REST API method
 *
 * @return array
 */
$result_jmoodle = $moodle->request('core_webservice_get_site_info');
```

Look at file of Joomla `Form` field like `MoodleinfoField`, here is an example:
```php
use Webtolk\JMoodle\JMoodle;

$moodle = new JMoodle();

// Check can we do the request to Moodle - have we Moodle credentials filled? 
if (!$moodle::canDoRequest())
{
    return ''; // or false or other that you need
}

// Make the request to Moodle webservice method. Look at Moodle docs
// Returns array with Moodle data or an error object
$result_jmoodle = $moodle->request('core_webservice_get_site_info');

// Check if we have an empty response for core_webservice_get_site_info method
if (count($result_jmoodle) == 0)
{
    return '<div class="alert alert-danger row">
                <div class="col-2 h1">400</div>
                <div class="col-10">There is no Moodle host response</div>
            </div>';
}

// Check if we have an error data from JMoodle library

if (isset($result_jmoodle['error_code']) && !empty($result_jmoodle['error_code']))
{
    return '<div class="alert alert-danger row">
                <div class="col-2 h1">' . $result_jmoodle['error_code'] . '</div>
                <div class="col-10">' . $result_jmoodle['error_message'] . '</div>
            </div>';
}

// Check if we have a wrong reponse from Moodle, but HTTP code is 200
if (!array_key_exists('sitename', $result_jmoodle) || empty($result_jmoodle['sitename']))
{
    return '<div class="alert alert-danger row">
                <div class="col-2 h1">400</div>
                <div class="col-10">Moodle return wrong response</div>
            </div>';
}

// All OK, we can access to Moodle data in Joomla
$result_jmoodle['sitename']; // 'My awesome test Moodle'
$result_jmoodle['release']; // 'Moodle 4.3 (Build: 20231009)'
$result_jmoodle['userpictureurl']; // 'Here your special Joomla user in Moodle picture url'

// etc...

```

# Library structure
This library is designed for Joomla developers. The library files are located in `libraries/Webtolk/JMoodle/src`.
```text
- src
-- Fields
-- Helper
-- Interfaces
- JMoodle.php
- JMoodleClientException.php
```
## Fields
**Fields** - there are Joomla native `Form` (ex. JForm) fields, which you can use in your extensions: modules, plugins, components etc. **This folder will be fill up**.

Your form XML example (Joomla 4 and Joomla 5):
### Moodleinfo Joomla field
This field will display info about your Moodle. If you see that info - your connection between Joomla and Moodle is fine.
```xml
<field addfieldprefix="Webtolk\JMoodle\Fields"
                       type="moodleinfo"
                       name="moodleinfo"/>
```

### Moodlerestapimethods Joomla field
This field displays you all available form Joomla Moodle REST API methods for token you have specify.
```xml
<field addfieldprefix="Webtolk\JMoodle\Fields"
                       type="moodlerestapimethods"
                       name="moodlerestapimethods"
                       collapsible="true"
                />
```
A `collapsible="true"` attribute is optional. If it is not set you will see whole Moodle REST API methods list.

## Helper
**Helper** is a folder where helpers for Moodle webservices methods are placed. These helpers check the structure of the transmitted data and data types before making a request to Moodle. If the structure and data types do not match the Moodle documentation, an error object with an error description is returned.
### Helper class name
The name of the helper class is formed dynamically in the method `getMethodHelperClass` based (file: `libraries\Webtolk\JMoodle\src\JMoodle.php`) on the name of the requested REST API Moodle method according to the following logic:
- get the Moodle webserivec method name
- explode it to parts by underscore `_`
So the helper class for `core_webservice_get_site_info` will found in Webtolk\JMoodle\Helper\ **Core\Webservice** namespace - **Webtolk\JMoodle\Helper\Core\Webservice**.
If the class name contains the word `Self`, we rename it to `MySelf`. So Helpers fo Moodle webservices `enrol_self_...` methods are placed in Enrol\**My**Self namespace - **Webtolk\JMoodle\Helper\Enrol\MySelf** - because the word Self is reserved in PHP. 
### Empty method helper class example 
```php
namespace Webtolk\JMoodle\Helper\Core\Create;

defined('_JEXEC') or die;
use Webtolk\JMoodle\Interfaces\MethodHelperInterface;

class Create implements MethodhelperInterface
{
    public function checkData(string $method, array $data = []): array
    {
        return $data;
    }
}
```
### Helper class example for Core\User namespace

```php
namespace Webtolk\JMoodle\Helper\Core\User;

defined('_JEXEC') or die;

use Webtolk\JMoodle\Interfaces\MethodHelperInterface;

class User implements MethodhelperInterface
{
	public function checkData(string $method, array $data = []): array
	{
	    // Call a check method
		return $this->$method($data);
	}

	/**
	 * Check data for core_user_create_users Moodle REST API method
	 *
	 * @param   array  $data  Users data for create in Moodle
	 *
	 * @return array $data array if check is success. Array with error description if check is false
	 *
	 * @since 1.0.0
	 */
	private function core_user_create_users(array $data = []): array
	{
		if (!array_key_exists('users', $data))
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		$users = $data['users'];

		if (count($users) < 1)
		{
			return [
				'error_code'    => 400,
				'error_message' => 'Empty users array specified'
			];
		}

		foreach ($users as $user)
		{
			if (!array_key_exists('createpassword', $user) || $user['createpassword'] != 1)
			{
				if (!array_key_exists('password', $user) || empty($user['password']))
				{
					return [
						'error_code'    => 400,
						'error_message' => 'Invalid password: you must provide a password, or set createpassword.'
					];
				}
			}

			if (!array_key_exists('username', $user) ||
				!array_key_exists('email', $user) ||
				!array_key_exists('firstname', $user) ||
				!array_key_exists('lastname', $user) ||
				empty($user['username']) ||
				empty($user['email']) ||
				empty($user['firstname']) ||
				empty($user['lastname'])
			)
			{
				return [
					'error_code'    => 400,
					'error_message' => 'One of the required fields (username, email, firstname, lastname) for user which you are creating are not specified or empty'
				];
			}
		}

		return $data;
	}
// And so on...
}
```
And so on for Moodle webservices methods:
- core_user_update_users
- core_user_delete_users
- core_user_get_users
- core_user_get_users_by_field
- core_user_add_user_device
- core_user_add_user_private_files
- core_user_agree_site_policy
- core_user_get_course_user_profiles
- core_user_get_private_files_info
- core_user_get_user_preferences
- core_user_remove_user_device
- core_user_search_identity
- core_user_set_user_preferences
- core_user_update_picture
- core_user_update_user_device_public_key
- core_user_update_user_preferences
- core_user_view_user_list
- core_user_view_user_profile

### Ready-made helper methods
- Webtolk\JMoodle\Helper\Core\User
- Webtolk\JMoodle\Helper\Enrol\Manual
## Interfaces
This folder contains interfaces for the library, which fix the structure of methods and their data for correct operation.
