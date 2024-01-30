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
