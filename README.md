# Discord

 - Class 'Discord' is consisted of class itself and every subclasses needed for this library.
 - The title class(\Discord) builds The Gateway API(https://discord.com/developers/docs/topics/gateway)
   needed for the entire bot run.
 - This library assumes that your bot includes all privileged intents: 'GUILD_PRESENCES', "GUILD_MEMBERS', and 'MESSAGE_CONTENT'.
 - Sending more than 120 Gateway requests in every 60 seconds can make your bot rate limited by Discord Server.
 - This library does not support bot server sharding.
 - Since this class functions include some 'echo' functions, your PHP console can show some messages.
   If you want to get rid of them, you can delete their original 'echo' functions.

## Help

> The statements below are for PHP or programming beginners.
> Advanced users are good to pass it.

 - To use class internal functions, you must declare the intended class as an object using 'new' indicator, as below.
```php
    $appcmd = new \Discord\AppCommand
```
 - Then, you can use functions, get or set attributes of the class.
```php
    $appcmd->id = Null;
        // sets the attribute 'id' as Null. This is done when you have to pass the object as a parameter.

    $NewVariable = $appcmd->EventData;
        // gets the attribute 'id' value. This is done when you recieve the data from Discord API, and you are free to use it!

    $appcmd->get_global_list();
        // executes the function 'get_global_list()'. 
```
 - Some Functions have parameters with '?' in front of its datatype. This means that the parameter is 'Nullable'. You can pass a value to the parameter or not, whatever you want.
```php
    $appcmd->get_global_list();
    $appcmd->get_global_list(True);
        // since the function's parameter 'with_localizations' is Nullable, both are valid to use.
```

## Setting Up

### Config File

 - This library needs a JSON config file which contains information about your discord bot.
 - The file name must be 'config.json'.
 - The file must be located right in the library folder.
 - The file must contain these keys and according values: "token", "app_id", and "guild_id".
 - "token" means your Bot Token and "app_id" means your Bot Application ID. Both can be viewd at your Discord Developer Portal.

### Library Contained

 - This library contains the library 'ReactPHP'.
 - You can see more infos here: https://github.com/reactphp

## SubClasses

 - Reference
   - API Reference
     - [Discord\DataFormat\Image]
 - Interactions
   - Application Command
     - [Discord\AppCommand](#discord-appcommand)
     - [Discord\AppCommand\Options](#discord-appcommand-options)
     - [Discord\AppCommand\Options\Choices](#discord-appcommand-options-choices)
   - Message Components
     - [Discord\Channel\Message\Components](#discord-channel-message-components)
     - [Discord\Channel\Message\Components\Buttons](#discord-channel-message-components-buttons)
     - [Discord\Channel\Message\Components\TextInputs](#discord-channel-message-components-textinputs)
     - [Discord\Channel\Message\Components\SelectMenus](#discord-channel-message-components-selectmenus)
   - Common Interaction
     - [Discord\Interaction]
     - [Discord\Interaction\Data\AppCommandData]
     - [Discord\Interaction\Data\AppCommandData\Option]
     - [Discord\Interaction\Data\MessageComponentData]
     - [Discord\Interaction\Data\ModalSubmitData]
     - [Discord\Interaction\Data\ResolvedData]
     - [Discord\Interaction\Response]
     - [Discord\Interaction\Response\AutoComplete]
     - [Discord\Interaction\Response\Messages]
     - [Discord\Interaction\Response\Modal]
   - Message Interaction
     - [Discord\MessageInteraction]
 - Applications
   - Application
     - [Discord\Application]
     - [Discord\Application\InstallParams]
   - Application Role Connection
     - [Discord\Application\RoleConnection]
     - [Discord\Application\RoleConnection\Metadata]
 - Users
   - User
     - [Discord\User]
     - [Discord\User\ApplicationRoleConnection]
     - [Discord\User\ApplicationRoleConnection\Metadata]
     - [Discord\User\Connection]
 - Channels
   - Channel
     - [Discord\Channel]
     - [Discord\Channel\ArchivedThreads]
     - [Discord\Channel\DefaultReaction]
     - [Discord\Channel\FollowedChannel]
     - [Discord\Channel\ForumTag]
     - [Discord\Channel\Overwrite]
     - [Discord\Channel\ThreadMember]
   - StageInstance
     - [Discord\StageInstance]
 - Messages
   - Message
     - [Discord\Channel\Message]
     - [Discord\Channel\Message\AllowedMentions]
     - [Discord\Channel\Message\Attachments]
     - [Discord\Channel\Message\Components]
     - [Discord\Channel\Message\Components\Buttons]
     - [Discord\Channel\Message\Components\SelectMenus]
     - [Discord\Channel\Message\Components\TextInputs]
     - [Discord\Channel\Message\Embeds]
     - [Discord\Channel\Message\Embeds\Author]
     - [Discord\Channel\Message\Embeds\Fields]
     - [Discord\Channel\Message\Embeds\Footer]
     - [Discord\Channel\Message\Embeds\Image]
     - [Discord\Channel\Message\Embeds\Provider]
     - [Discord\Channel\Message\Embeds\Thumbnail]
     - [Discord\Channel\Message\Embeds\Video]
     - [Discord\Channel\Message\Reference]
   - Emoji
     - [Discord\Emoji]
   - Sticker
     - [Discord\Sticker]
     - [Discord\Sticker\Pack]
   - Webhook
     - [Discord\Webhook]
 - Guilds
   - Guild
     - [Discord\Guild]
     - [Discord\Guild\Ban]
     - [Discord\Guild\GuildOnboarding]
     - [Discord\Guild\GuildOnboarding\Prompts]
     - [Discord\Guild\GuildOnboarding\Prompts\Options]
     - [Discord\Guild\Integration]
     - [Discord\Guild\Integration\Account]
     - [Discord\Guild\Integration\Application]
     - [Discord\Guild\Member]
     - [Discord\Guild\Preview]
     - [Discord\Guild\Prune]
     - [Discord\Guild\Role]
     - [Discord\Guild\WelcomeScreen]
     - [Discord\Guild\WelcomeScreen\Channel]
     - [Discord\Guild\Widget]
     - [Discord\Guild\WidgetSettings]
   - AuditLog
     - [Discord\AuditLog]
     - [Discord\AuditLog\Change]
     - [Discord\AuditLog\Entry]
     - [Discord\AuditLog\Entry\OptionalInfo]
   - AutoModeration
     - [Discord\AutoModeration\Action]
     - [Discord\AutoModeration\Action\Metadata]
     - [Discord\AutoModeration\Rule]
     - [Discord\AutoModeration\Rule\TriggerMetadata]
   - Guild Scheduled Event
     - [Discord\GuildScheduledEvent]
     - [Discord\GuildScheduledEvent\EntityMetadata]
     - [Discord\GuildScheduledEvent\User]
   - Guild Template
     - [Discord\GuildTemplate]
   - Invite
     - [Discord\Invite]
     - [Discord\Invite\InviteMetadata]
   - Voice
     - [Discord\Voice\Region]
       
## Special Values

### <a id="locales">Locales [string]</a>

 - **"id"**	: Indonesian	(Bahasa Indonesia)
 - **"da"** : Danish (Dansk)
 - **"de"** : German (Deutsch)
 - **"en-GB"** : English, UK (English, UK)
 - **"en-US"** : English, US (English, US)
 - **"es-ES"** : Spanish (Español)
 - **"fr"** : French (Français)
 - **"hr"** : Croatian (Hrvatski)
 - **"it"** : Italian (Italiano)
 - **"lt"** : Lithuanian (Lietuviškai)
 - **"hu"** : Hungarian (Magyar)
 - **"nl"** : Dutch (Nederlands)
 - **"no"** : Norwegian (Norsk)
 - **"pl"** : Polish (Polski)
 - **"pt-BR"** : Portuguese, Brazilian (Português do Brasil)
 - **"ro"** : Romanian, Romania (Română)
 - **"fi"** : Finnish (Suomi)
 - **"sv-SE"** : Swedish (Svenska)
 - **"vi"** : Vietnamese (Tiếng Việt)
 - **"tr"** : Turkish (Türkçe)
 - **"cs"** : Czech (Čeština)
 - **"el"** : Greek (Ελληνικά)
 - **"bg"** : Bulgarian (български)
 - **"ru"** : Russian (Pусский)
 - **"uk"** : Ukrainian (Українська)
 - **"hi"** : Hindi (हिन्दी)
 - **"th"** : Thai (ไทย)
 - **"zh-CN"** : Chinese, China (中文)
 - **"ja"** : Japanese (日本語)
 - **"zh-TW"** : Chinese, Taiwan (繁體中文)
 - **"ko"** : Korean (한국어)

## Functions

### OnEvent()

 - Syntax: **OnEvent(string $event, callback $callback)**
 - Usage: Runs the callback function when the specified event happens. <br/>
   (Actually, it is when the bot program recieves the event from the Gateway API.)
 - Example:

```php
    $client = new \Discord;
    $client->OnEvent("Ready", function($data){
    var_dump($data);
    });
```

### run()

> You MUST execute this function at the very last of your php script.
> Every code after this function may get ignored or cause errors.

 - Syntax: **run()**
 - Usage: Initiates and runs the bot, using Gateway API.

# <a id="discord-dataformat-image">Discord\DataFormat\Image</a>

## Function

### set()

> Whenever you use this function again and again, the file gets overwritten.

 - Syntax : **set(string $FilePath)**
 - Usage : Sets the class object as the selected image file. You can now pass the object itself as a parameter to upload image data.

# <a id="discord-appcommand">Discord\AppCommand</a>

## Types

### Slash Commands

 - Slash Commands refer to application commands with the 'CHAT_INPUT' type.
 - You can set your slash commands to have subcommands or subcommand groups, which organize your commands and subcommands.
 - Discord Official API organizes slash commands tree as below:
   > Slash Commands > Subcommands Groups > Subcommand <br/>
 - However, as it also represents, you are free to organize the tree however you'd like.
 - If you set lower-level commands(such as Subcommands or Subcommand Groups), you cannot use your higher-level command alone.
 - You **cannot** have Subcommand Group under another Subcommand Group or Subcommand. It **must** be right below Slash Commands.

### User Commands

 - User commands are application commands that appear on the context menu (right click or tap) of users.
 - To use user commands, a user must have permissions to send text messages in the channel they invoke a user command in.
 - 'description' attribute **must** be set to empty string("").

### Message Commands

 - Message commands are application commands that appear on the context menu (right click or tap) of messages.
 - 'description' attribute **must** be set to empty string("").

## <a id="discord-appcommand-namerestrictions">Name Restrictions</a>

 - Your bot **cannot** have
    - two global 'CHAT_INPUT' commands with the same name.
    - two guild 'CHAT_INPUT' commands within the same name on the same guild.
    - two global 'USER' commands with the same name.

 - Your bot **can** have
    - a global and guild 'CHAT_INPUT' command with the same name.
    - a global 'CHAT_INPUT' and 'USER' command with the same name.
    - same command same as other bots.
    - up to 100 'CHAT_INPUT' commands, 5 'USER' commands, 5 'MESSAGE' commands within each scope(global and guild).

 - Your bot name **must** follow the following regex:
   **<a id="appcommand-name-regex">"^[-_\p{L}\p{N}\p{sc=Deva}\p{sc=Thai}]{1,32}$"</a>**

## Rate Limits

> There is global rate limit of 200 application command creates per day and per guild.

## User Permission

> When updating command permissions, the user must have the following permissions:
> - Permission to Manage Guild and Manage Roles
> - Permission to manage the resources affected(roles/users/channels)

## Attributes

 - id : **[string|snowflake]** Unique ID of command
 - type : **[int|[Application Command Type](#application-command-type)]** Type of command, defaults to 1
 - application_id : **[string|snowflake]** ID of the parent application
 - guild_id : **[string|snowflake]** Guild ID of the command, if not global
 - name: **[string]** 	Name of command, 1-32 characters. Must match the [Name Restriction Regex](#appcommand-name-regex).
 - name_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for name field. Values follow the same restrictions as name
 - description : **[string]** Description for CHAT_INPUT commands, 1-100 characters. Empty string for USER and MESSAGE commands
 - description_localization : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for description field. Values follow the same restrictions as description
 - options : **[array]** Array of [Application Command Option](#discord-appcommand-options) objects. Parameters for the command, max of 25. Only valid for CHAT_INPUT app commands.
 - default_member_permissions : **[string]** Set of permissions represented as a bit set. To disable the command for everyone except admins by default, set this attribute value to "0".
 - dm_permission : **[bool]** Indicates whether the command is available in DMs with the app, only for globally-scoped commands. By default, commands are visible.
 - nsfw : **[bool]** Indicates whether the command is age-restricted, defaults to false
 - version : **[string|snowflake]** Autoincrementing version identifier updated during substantial record changes

## Special Values

### <a id="application-command-type">Application Command Type [int]</a>

 - **"CHAT_INPUT | 1"** : Slash commands; a text-based command that shows up when a user types /
 - **"USER | 2"** : A UI-based command that shows up when you right click or tap on a user
 - **"MESSAGE | 3"** : A UI-based command that shows up when you right click or tap on a message

## <a id="discord-appcommand-options">Discord\AppCommand\Options</a>

### Attributes

> 'autocomplete' may not be set to 'true' if 'choices' are present.

 - type : **[int|[Application Command Option Type](#application-command-option-type)]** Type of option
 - name : **[string]** 	1-32 character name. Must match the [Name Restriction Regex](#appcommand-name-regex)
 - name_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the name field. Values follow the same restrictions as name
 - description : **[string]** 1-100 character description
 - description_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the description field. Values follow the same restrictions as description
 - required : **[bool]** If the parameter is required or optional--default false
 - choices : **[array]** Array of [Discord\AppCommand\Options\Choices](#discord-appcommand-options-choices) objects. Choices for STRING, INTEGER, and NUMBER types for the user to pick from, max 25
 - options : **[array]** Array of [Discord\AppCommand\Options](#discord-appcommand-options) objects. If the option is a subcommand or subcommand group type, these nested options will be the parameters
 - channel_type : **[array]** Array of [Channel Types](#channel-types) numbers(int). If the option is a channel type, the channels shown will be restricted to these types
 - min_value : **[int|float]** Int for 'INTEGER' options, float for 'NUMBER' options. If the option is an INTEGER or NUMBER type, the minimum value permitted
 - max_value : **[int|float]** Int for 'INTEGER' options, float for 'NUMBER' options. If the option is an INTEGER or NUMBER type, the maximum value permitted
 - min_length : **[int]**	For option type STRING, the minimum allowed length (minimum of 0, maximum of 6000)
 - max_length : **[int]** For option type STRING, the maximum allowed length (minimum of 1, maximum of 6000)
 - autocomplete : **[bool]** If autocomplete interactions are enabled for this STRING, INTEGER, or NUMBER type option

### Special Values

#### <a id="application-command-option-type">Application Command Option Type [int]</a>

 - **"SUB_COMMAND | 1"**
 - **"SUB_COMMAND_GROUP | 2"**
 - **"STRING | 3"**
 - **"INTEGER | 4"** : Any Integer between -2^53 and 2^53
 - **"BOOLEAN | 5"**
 - **"USER | 6"**
 - **"CHANNEL | 7"** : Includes all channel types & categories
 - **"ROLE | 8"**
 - **"MENTIONABLE | 9"** : Refers to users and roles
 - **"NUMBER | 10"** : Any float between -2^53 and 2^53
 - **"ATTACHMENT | 11"** : [Discord\Channel\Message\Attachments](#discord-channel-message-attachments) object

## <a id="discord-appcommand-options-choices">Discord\AppCommand\Options\Choices</a>

### Attributes

 - name : **[string]** 1-100 character choice name
 - name_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the name field. Values follow the same restrictions as name
 - value : **[string|int|float]** Value for the choice, up to 100 characters if string

## Functions

### <a id="discord-appcommand-f-get-global-list">get_global_list()</a>

 - Syntax : get_global_list(bool ?$with_localizations)
 - Usage : Fetches all of the global commands for your bot application. Returns an array of [Discord\AppCommand](#discord-appcommand) objects. 
 - Parameters :
    - with_localizations : **[bool]** whether to include full localization dictionaries('name_localizations' and 'description_localizations'). Default to 'False'.

### <a id = "discord-appcommand-f-create-global">create_global()</a>

> Creating a command with the same name as an existing command for your bot will overwrite the old command. See more infos in [Name Restrictions](#discord-appcommand-namerestrictions)

 - Syntax : create_globals(string $name, ?array name_localizations, ?string $description, ?array description_localizations, ?array $options, ?string $default_member_permissions, ?bool $dm_permission, ?int $type, ?bool $nsfw)
 - Usage : Create a new global command. The function returns HTTP Staus Code. If '201' a new command is successfully created, and if '200' the existed command with the same name was overwritten. Sets the class object's attribute as [Discord\AppCommand](#discord-appcommand) object's attribute.
 - Parameters :
    - name : **[string]** Name of command, 1-32 characters. Must match the [Name Restriction Regex](#appcommand-name-regex)
    - name_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the 'name' field. Values follow the same restrictions as name.
    - description : **[string]** 1-100 character description for 'CHAT_INPUT' commands.
    - description_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the 'description' field. Values follow the same restrictions as 'description'.
    - options : **[array]** Array of [Discord\AppCommand\Options](#discord-appcommand-options) objects. The parameters for the command.
    - default_member_permissions : **[string]** Set of [Permissions](#permissions) represented as a bit set.
    - dm_permission : **[bool]** Indicates whether command is available in DMs with the app, only for globally-scoped commands. By default, commands are visible.
    - type : **[int|[Application Command Type](#application-command-type)]** Type of command, defaults '1' if not set.
    - nsfw : **[bool]** Indicates whether the command is age-restricted.
  
### get_global()

 - Syntax : get_global(string $command_id)
 - Usage : Fetch a global command for your bot. Returns an [Discord\AppCommand](#discord-appcommand) object.
 - Parameters :
    - command_id : **[string|snowflake]** The ID of your command, generated when you created one. You can get it from responses of functions [get_global_list()](#discord-appcommand-f-get-global-list) or [create_global()](#discord-appcommand-f-create-global).

### edit_global()

> The following accords to parameters except 'command_id'.
> If you provide any parameter, although some are passed as 'Null', all fields will entirely be overwritten.

 - Syntax : edit_global(string $command_id, ?string $name, ?array $name_localizations, ?string $description, ?array $description_localizations, ?array $options, ?string $default_member_permissions, ?bool $dm_permission, ?bool $default_permission, ?bool $nsfw)
 - Usage : Edit a global command. Returns HTTP Status Code '200' if successful. Sets the class object's attributes as [Discord\AppCommand] object's attributes.
 - Parameters :
    - command_id : **[string|snowflake]** The ID of your command, generated when you created one. You can get it from responses of functions [get_global_list()](#discord-appcommand-f-get-global-list) or [create_global()](#discord-appcommand-f-create-global).
    - name : **[string]** Name of command, 1-32 characters. Must match the [Name Restriction Regex](#appcommand-name-regex)
    - name_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the 'name' field. Values follow the same restrictions as name.
    - description : **[string]** 1-100 character description for 'CHAT_INPUT' commands.
    - description_localizations : **[dictionary]** Dictionary with [Available Locales](#locales) keys. Localization dictionary for the 'description' field. Values follow the same restrictions as 'description'.
    - options : **[array]** Array of [Discord\AppCommand\Options](#discord-appcommand-options) objects. The parameters for the command.
    - default_member_permissions : **[string]** Set of [Permissions](#permissions) represented as a bit set.
    - dm_permission : **[bool]** Indicates whether command is available in DMs with the app, only for globally-scoped commands. By default, commands are visible.
    - type : **[int|[Application Command Type](#application-command-type)]** Type of command, defaults '1' if not set.
    - nsfw : **[bool]** Indicates whether the command is age-restricted.
  
### delete_global()

 - Syntax : delete_global(string $command_id)
 - Usage : Deletes a global command. Returns the HTTP Status Code '204' on success.
 - Parameters :
    - command_id : **[string|snowflake]** The ID of your command, generated when you created one. You can get it from responses of functions [get_global_list()](#discord-appcommand-f-get-global-list) or [create_global()](#discord-appcommand-f-create-global).

### Overwrite Global Application Commands

> This method is consisted of sets of functions. You must follow the following steps:
> 1. Use enough add_global() functions you want to overwrite.
> 2. Execute overwrite_global() function to overwrite all global application commands with your added commands.
> - Be sure to execute overwrite_global() function after all command adding is done! All commands after the execution would be ignored.
> - Your all existing global application commands would be overwritten.

#### add_global()

 - Syntax : add_global(string $name, ?array $name_localizations, ?string $description, ?array $description_localizations, ?array $options, ?string $default_member_permissions, ?bool $dm_permission, ?int $type, ?bool $nsfw)
 - Usage : Adds your application command to the overwrite request queue list.
 - Parameters : Exactly same as [create_global()](#discord-appcommand-f-create-global) function of this class. See the link above for further information.

#### overwrite_global()

 - Syntax : overwrite_global()
 - Usage : Runs the overwrite request queue. Returns a dictionary with two keys('code' and 'data'). The key 'code' includes the HTTP Status Code '200' on success. Another key 'data' contains an array of [Discord\AppCommand](#discord-appcommand) objects.
