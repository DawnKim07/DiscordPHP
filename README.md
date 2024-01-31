<h1>Discord</h1>

 - Class 'Discord' is consisted of class itself and every subclasses needed for this library.
 - The title class(\Discord) builds The Gateway API(https://discord.com/developers/docs/topics/gateway)
   needed for the entire bot run.
 - This library assumes that your bot includes all privileged intents: 'GUILD_PRESENCES', "GUILD_MEMBERS', and
   'MESSAGE_CONTENT'.
 - Sending more than 120 Gateway requests in every 60 seconds can make your bot rate limited by Discord Server.
 - This library does not support bot server sharding.
 - Since this class functions include some 'echo' functions, your PHP console can show some messages.
   If you want to get rid of them, you can delete their original 'echo' functions.

<h2>Setting Up</h2>

<h3>Config File</h3>

 - This library needs a JSON config file which contains information about your discord bot.
 - The file name must be 'config.json'.
 - The file must be located right in the library folder.
 - The file must contain these keys and according values: "token", "app_id", and "guild_id".
 - "token" means your Bot Token and "app_id" means your Bot Application ID. Both can be viewd at your Discord
   Developer Portal.

<h3>Library Contained</h3>

 - This library contains the library 'ReactPHP'.
 - You can see more infos here: https://github.com/reactphp

<h2>Functions</h2>

<h3>OnEvent()</h3>

 - Syntax: OnEvent(string $event, callback $callback)
 - Usage: Runs the callback function when the specified event happens.<br/>
   (Actually, it is when the bot program recieves the event from the Gateway API.)
 - Example: <br/>
   ![image](https://github.com/Xero-L/DiscordPHP/assets/147680492/575a4c45-e5aa-41db-9914-d3bced251f4d)

<h3>run()</h3>

 - Syntax: run()
 - Usage: Initiates and runs the bot, using Gateway API.
 - Notice: You MUST execute this function at the very last of your php script.
   Every code after this function may get ignored or cause errors.


<h1>Discord\AppCommand</h1>

<h2>Attributes</h2>

 - id : <b>[string|snowflake]</b> Unique ID of command
 - type : <b>[int|Application Command Type]</b> Type of command, defaults to 1
 - application_id : <b>[string|snowflake]</b> ID of the parent application
 - guild_id : <b>[string|snowflake]</b> Guild ID of the command, if not global
 - name: <b>[string]</b> 	Name of command, 1-32 characters. Must match the following regex:
   <b>^[-_\p{L}\p{N}\p{sc=Deva}\p{sc=Thai}]{1,32}$</b>
 - name_localizations : <b>[dictionary|Available Locales]</b> Localization dictionary for name field. Values follow the same restrictions as name
 - description : <b>[string]</b> Description for CHAT_INPUT commands, 1-100 characters. Empty string for USER and MESSAGE commands
 - description_localization : <b>[dictionary|Available Locales]</b> Localization dictionary for description field. Values follow the same restrictions as description
 - options : <b>[array|Application Command Option]</b> Parameters for the command, max of 25. Only valid for CHAT_INPUT app commands.
 - default_member_permissions : <b>[string]</b> Set of permissions represented as a bit set. To disable the command for everyone except admins by default, set this attribute value to "0".
 - dm_permission : <b>[bool]</b> Indicates whether the command is available in DMs with the app, only for globally-scoped commands. By default, commands are visible.
 - nsfw : <b>[bool]</b> Indicates whether the command is age-restricted, defaults to false
 - version : <b>[string|snowflake]</b> Autoincrementing version identifier updated during substantial record changes

<h2></h2>
