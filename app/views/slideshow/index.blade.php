<!doctype>
<html>
	
	<head>
		<title>Slideshow</title>
		<link rel="stylesheet" href="{{asset('css/reset.css')}}" type="text/css" />
		<link href='https://fonts.googleapis.com/css?family=Nunito:400,700,300' rel='stylesheet' type='text/css'>
		<style type="text/css">
			#container {
				width:1024px;
				height:768px;
				background-image: url({{asset('img/baggrund-slideshow.png')}});
				background-size:contain;
				position:relative;
			}
			.next-up {
				position:absolute;
				left:40px;
				right:40px;
				top:40px;
				height:80px;
				background:rgba(0,0,0,0.8);
				font-size:40px;
				color:#fff;
				line-height:80px;
				padding:0 10px;
				text-align:center;
				font-family:nunito-light,"helvetica neue",verdana;
				overflow:hidden;
			}
			.slideshow {
				position:absolute;
				left:40px;
				right:40px;
				top: 160px;
				bottom:40px;
				background:rgba(0,0,0,0.8);
				padding:20px;
			}
			.slideshow h1, .slideshow h2, .slideshow h3, .slideshow h4, .slideshow h5,  .slideshow h6, .slideshow p {
				font-family:Nunito-light,"Helvetica Neue",Verdana;
				color:#fff;
				text-align:center;
				margin-bottom:20px;
			}
			.slideshow h1 {
				font-size:48px;
				font-weight:bold;
			}
			.slideshow h2 {
				font-size: 34px;
			}
			.slideshow h3 {
				font-size:28px;
			}
			.slideshow p {
				font-size:20px;
			}
		</style>
	</head>
	<body>
		<div id="container">
			<div class="next-up"></div>
			<div class="slideshow">
				
				
			</div>
		</div>
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script type='text/javascript' src='https://rawgithub.com/aamirafridi/jQuery.Marquee/master/jquery.marquee.min.js'></script>
		<script>
		(function($) {
			var nextup = $('.next-up');
			var slideshow = $('.slideshow');
			var currentSlide = 0;
			
			function updateNextup() {
				$.get('/slideshow/nextup').success(function(data) {
					if (data.nextup) {
						nextup.text(data.nextup.name + ' @ '+data.nextup.at);
						nextup.marquee({duplicated:true,});
					}
				});
			}
			function updateSlide() {
				$.get('/slideshow/slide/'+currentSlide).success(function(data) {
					console.log(data);
					currentSlide = data.id;
					slideshow.html(data.content);
					setTimeout(updateSlide,data.duration*1000);
				});
			}
			updateNextup();
			updateSlide();
			$(document).ready(function() {
				setInterval(updateNextup,120000);
			});
		})(jQuery);
			
		</script>
	</body>
	
</html>