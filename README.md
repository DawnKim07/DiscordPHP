# Discord

 - Class 'Discord' is consisted of class itself and every subclasses needed for this library.
 - The title class(\Discord) builds The Gateway API(https://discord.com/developers/docs/topics/gateway)
   needed for the entire bot run.
 - This library assumes that your bot includes all privileged intents: 'GUILD_PRESENCES', "GUILD_MEMBERS', and 'MESSAGE_CONTENT'.
 - Sending more than 120 Gateway requests in every 60 seconds can make your bot rate limited by Discord Server.
 - This library does not support bot server sharding.
 - Since this class functions include some 'echo' functions, your PHP console can show some messages.
   If you want to get rid of them, you can delete their original 'echo' functions.

## SubClasses

 - [Discord\AppCommand](#discord-appcommand)

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

## Functions

### OnEvent()

 - Syntax: OnEvent(string $event, callback $callback)
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

 - Syntax: run()
 - Usage: Initiates and runs the bot, using Gateway API.
 - Notice: You MUST execute this function at the very last of your php script.
   Every code after this function may get ignored or cause errors.


# <a id="discord-appcommand">Discord\AppCommand</a>

## Attributes

 - id : **[string|snowflake]** Unique ID of command
 - type : **[int|[Application Command Type](#application-command-type)]** Type of command, defaults to 1
 - application_id : **[string|snowflake]** ID of the parent application
 - guild_id : **[string|snowflake]** Guild ID of the command, if not global
 - name: **[string]** 	Name of command, 1-32 characters. Must match the following regex:
   **^[-_\p{L}\p{N}\p{sc=Deva}\p{sc=Thai}]{1,32}$**
 - name_localizations : **[dictionary|[Available Locales](#locales)]** Localization dictionary for name field. Values follow the same restrictions as name
 - description : **[string]** Description for CHAT_INPUT commands, 1-100 characters. Empty string for USER and MESSAGE commands
 - description_localization : **[dictionary|[Available Locales](#locales)]** Localization dictionary for description field. Values follow the same restrictions as description
 - options : **[array|Application Command Option]** Parameters for the command, max of 25. Only valid for CHAT_INPUT app commands.
 - default_member_permissions : **[string]** Set of permissions represented as a bit set. To disable the command for everyone except admins by default, set this attribute value to "0".
 - dm_permission : **[bool]** Indicates whether the command is available in DMs with the app, only for globally-scoped commands. By default, commands are visible.
 - nsfw : **[bool]** Indicates whether the command is age-restricted, defaults to false
 - version : **[string|snowflake]** Autoincrementing version identifier updated during substantial record changes

## Special Values

### [Application Command Type]

 - **[CHAT_INPUT | 1]** : Slash commands; a text-based command that shows up when a user types /
 - **[USER | 2]** : A UI-based command that shows up when you right click or tap on a user
 - **[MESSAGE | 3]** : A UI-based command that shows up when you right click or tap on a message

