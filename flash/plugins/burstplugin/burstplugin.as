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
		
		/** Let the player know what the name of your plugin is. **/
		public function get id():String { return "burstplugin"; }

		/** Constructor **/
		public function burstplugin() {
		}
		
		public function resize(wid:Number, hei:Number):void {
		}
		
		/**
		 * Called by the player after the plugin has been created.
		 *  
		 * @param player A reference to the player's API
		 * @param config The plugin's configuration parameters.
		 */
		public function initPlugin(player:IPlayer, config:PluginConfig):void {
			api = player;
			
			var streamUrl:String = 'http://music-stream.burst-dev.com/stream/';
			var streamSecret:String = 'theqa3ExUs92f4uNADrebR5sTusWadREJa5AP3U4AZ6fERA7aQaTaheFU7asufru';
			var xid:String = config['xid'];
			
			var request:URLRequest = new URLRequest("http://music.burst-dev.com/load/"+xid);
			request.method = URLRequestMethod.POST;

			var loader:URLLoader = new URLLoader();
			loader.dataFormat = URLLoaderDataFormat.VARIABLES;
			loader.addEventListener(Event.COMPLETE, completeHandler);
			loader.load(request);
			
			function completeHandler(evt:Event) {
				var linkType:String = evt.target.data.linkType;
				
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
					var md5Hash = MD5.encrypt (streamSecret+relPath+t_hex);

					var link:String = streamUrl+md5Hash+"/"+t_hex+relPath;
				} else
				{
					var link:String = evt.target.data.link;
				}
				
				// Debug.log(link);
				api.load(link);
				api.play();
				
			}
			
		}
		
	}
}