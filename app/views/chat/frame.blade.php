<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Candy - Chats are not dead yet</title>
	<link rel="shortcut icon" href="../res/img/favicon.png" type="image/gif" />
	<link rel="stylesheet" type="text/css" href="{{asset('res/default.css')}}" />

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="{{asset('js/libs.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('js/candy.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			Candy.init('{{url('http-bind')}}/', {
				core: {
					// only set this to true if developing / debugging errors
					debug: false,
					// autojoin is a *required* parameter if you don't have a plugin (e.g. roomPanel) for it
					//   true
					//     -> fetch info from server (NOTE: does only work with openfire server)
					//   ['test@conference.example.com']
					//     -> array of rooms to join after connecting
					autojoin: ['general@conference.aaulan.dk']
				},
				view: { assets: '{{url('res/')}}/' }
			});
			Candy.Core.attach('{{$jid}}','{{$sid}}','{{$rid}}');
			var user = Candy.Core.getUser();
			user.setNick('{{Auth::user()->display_sanitized}}');
			Candy.Core.setUser(user);
			/**
			 * Thanks for trying Candy!
			 *
			 * If you need more information, please see here:
			 *   - Setup instructions & config params: http://candy-chat.github.io/candy/#setup
			 *   - FAQ & more: https://github.com/candy-chat/candy/wiki
			 *
			 * Mailinglist for questions:
			 *   - http://groups.google.com/group/candy-chat
			 *
			 * Github issues for bugs:
			 *   - https://github.com/candy-chat/candy/issues
			 */
		});
	</script>
</head>
<body>
	<div id="candy"></div>
</body>
</html>
