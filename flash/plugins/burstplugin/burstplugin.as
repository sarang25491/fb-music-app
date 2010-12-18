package {
	import com.longtailvideo.jwplayer.events.MediaEvent;
	import com.longtailvideo.jwplayer.events.PlayerStateEvent;
	import com.longtailvideo.jwplayer.player.IPlayer;
	import com.longtailvideo.jwplayer.player.PlayerState;
	import com.longtailvideo.jwplayer.plugins.IPlugin;
	import com.longtailvideo.jwplayer.plugins.PluginConfig;
	
	import flash.display.Sprite;
	import flash.net.*;
	import flash.errors.IOError;
	import flash.events.*;
	import com.gsolo.encryption.MD5;
//	import com.carlcalderon.arthropod.Debug;
	
	public class burstplugin extends Sprite implements IPlugin {
		public var api:IPlayer;
      public var xid:String;		

		/** Let the player know what the name of your plugin is. **/
		public function get id():String { return "burstplugin"; }

		/** Constructor **/
		public function burstplugin() {
		}
	
      public function resize(wid:Number, hei:Number):void { } // Don't know why this is here, but it won't compile without it
	
		/**
		 * Called by the player after the plugin has been created.
		 *  
		 * @param player A reference to the player's API
		 * @param config The plugin's configuration parameters.
		 */

      private function callback(cmd:String):void
      {
         var request:URLRequest = new URLRequest("http://music.burst-dev.com/player/"+cmd+"/"+xid);
         request.method = URLRequestMethod.POST;

         var loader:URLLoader = new URLLoader();
         loader.dataFormat = URLLoaderDataFormat.VARIABLES;
         loader.load(request);
      }

      private function logFullPlay(evt:MediaEvent):void
      {
         callback("logFullPlay");
      }

		public function initPlugin(player:IPlayer, config:PluginConfig):void {
         api = player;
			
			var streamUrl:String = 'http://music-stream.burst-dev.com/stream/';
			var streamSecret:String = 'theqa3ExUs92f4uNADrebR5sTusWadREJa5AP3U4AZ6fERA7aQaTaheFU7asufru';
			xid = config['xid'];
			
			var request:URLRequest = new URLRequest("http://music.burst-dev.com/player/load/"+xid);
			request.method = URLRequestMethod.POST;

			var loader:URLLoader = new URLLoader();
			loader.dataFormat = URLLoaderDataFormat.VARIABLES;
			loader.addEventListener(Event.COMPLETE, completeHandler);
			loader.load(request);
	
			function completeHandler(evt:Event):void {
				var linkType:String = evt.target.data.linkType;
            var link:String;			
	
				// Debug.log(linkType);
				if (linkType == "1")
				{
					/*
					PHP will generate these variables for the plugin.
					fileName, drive, userFolder, t_hex
					*/
					var fileName:String = evt.target.data.filename;
					var drive:String = evt.target.data.drive;
					var userFolder:String = evt.target.data.userFolder;
					var t_hex:String = evt.target.data.t_hex;

					var relPath:String = "/"+drive+"/"+userFolder+"/"+fileName;
					var md5Hash:String = MD5.encrypt (streamSecret+relPath+t_hex);

					link = streamUrl+md5Hash+"/"+t_hex+relPath;
				} 
            else
				{
					link = evt.target.data.link;
				}
				
            api.addEventListener(MediaEvent.JWPLAYER_MEDIA_COMPLETE, logFullPlay)
				// Debug.log(link);
            
				api.load(link);
				api.play();
				
			}
			
		}
		
	}
}
