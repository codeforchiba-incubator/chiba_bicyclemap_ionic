<?php 
function printTitle ($title) {
	echo '<a name="' . strtolower($title) . '"></a>';
	echo '<h3><a href="#' . strtolower($title) . '">'.ucwords($title).':</a></h3>';
}
?>		
<!DOCTYPE html PUBLIC>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Ursudio" />
<meta name="robots" content="noindex, nofollow, noarchive" />
<meta name="viewport" content="width=820" />
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css"></link>
<link href='http://fonts.googleapis.com/css?family=Open+Sans|Fjalla+One' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="css/shCoreEclipse.css"/>
<style>
html { 
	background:#F0F0F0; 
	color:#555; 
	font-family:arial, sans-serif; font-family: 'Open Sans', sans-serif; 
}
h1, 
h2 { 
	text-shadow: #FFF 1px 1px 0; 
	font-family: 'Fjalla One', sans-serif; 
}
h3 { 
	color:#7CAD55; 
}
h3:before { 
	content:"#"; 
	color:#DDD; 
	position:absolute; 
	margin-left:-20px; 
}
a { 
	color:#7C9ED1; 
	text-decoration:none; 
	transition: all .3s; 
	-moz-transition: all .3s; 
	-webkit-transition: all .3s; 
}
a:hover { 
	color:#BDBD44; 
}
#navigation a:before, 
#navigation a:after { 
	margin-top:5px;
	transition: all .1s ease-out;
	-webkit-transition: all .1s ease-out;
	-moz-transition: all .1s ease-out;
	position: absolute;
	opacity:0;
	display:block;
	visibility:hidden;
}
#navigation a:before {
	content: '';
	border: 8px solid transparent;
	border-bottom-color:black;
	top:24px;
	left:35%;
}
#navigation a:after {
	content: attr(data-title);
	background: black;
	color: white;
	padding: 2px 7px;
	top: 40px;
	font-size:.8em;
	text-align:center;
	white-space:nowrap;
}
#navigation li.floatRight a:after {
	right:0;
}
#navigation a:hover:before, 
#navigation a:hover:after { 
	margin-top:0;
	opacity: 1;
	visibility: visible;
}

#navigation ul {
	padding: 0 50px;
}

a[name] {
	position:absolute;
	margin-top:-40px;
}
body { 
	margin:0; 
}
.container { 
	padding:15px 10%; 
}
.container.first { 
	padding:5px 0; 
	position:fixed; 
	z-index:9999; 
	width:100%; 
	background: #DDD;
}
.container.last { 
	background:#222 url(images/dark_wall.png); 
	min-height:185px; 
}
ul { 
	list-style:none; 
	margin:0; 
}
li { 
	line-height:40px; 
} 
#navigation ul { 
	list-style:none; 
	height:29px; 
	margin:0; 
}
#navigation li { 
	float:left; 
	line-height:initial; 
	position:relative;
}
#navigation li.floatRight {
	float:right;
}
li a { 
	padding: 12px 20px; 
	color:#222; 
	transition: all .3s; 
	-moz-transition: all .3s; 
	-webkit-transition: all .3s; 
}
li a:hover { 
	background-color:rgba(255,255,255,.2); 
}
li a img { 
	vertical-align:middle; 
}
#header { 	
	padding:100px 0 0; 
	text-align:center; 
}
#map { 
	width:100%; 
	height:500px; 
	margin:0 auto 40px;
}
#map a { 
	color:initial; 
}
canvas { 
	opacity: 1 
}
.syntaxhighlighter { 
	overflow-y:hidden !important; 
	padding: 10px 0; 
}
</style>
<!--[if lte IE 8]>
    <link rel="stylesheet" href="leaflet/dist/leaflet.ie.css" />
<![endif]-->
<title>
Web GL Heatmap Leaflet Plugin
</title>
</head>
	<body>
	<?php
	if (file_exists('ga.php')) {
		include 'ga.php';
	}
	?>
		<div class="first container">
			<div id="navigation">
				<ul>
					<li><a target="_blank" data-title="View on Github" href="https://github.com/ursudio/webgl-heatmap-leaflet"><img src="images/github_icon_20.png" height="20"
					 /></a></li>
					 <li class="floatRight"><a target="_blank" data-title="See more Ursudio projects" href="http://www.ursudio.com/"><img src="/apple-touch-icon.png" height="20"
					 /></a></li>
					 <li class="floatRight"><a target="_blank" data-title="Try out Leaflet" href="http://www.leafletjs.com/"><img src="images/leaflet_icon_20.png" height="20"
					 /></a></li>
					 <li class="floatRight"><a target="_blank" data-title="Map tiles from CloudMade" href="http://www.cloudmade.com/"><img src="images/cloudmade_icon_20.png" height="20"
					 /></a></li>
				</ul>
			</div>
		</div>
		<div class="container">
			<div id="header">
				<h1>WebGL Heatmap Leaflet Plugin</h1>
				<h2>Using the <a href="http://codeflow.org/entries/2013/feb/04/high-performance-js-heatmaps/" target="_blank">WebGL Heatmap library</a>, made by Florian Bösch <a href="https://twitter.com/pyalot" target="_blank">(@pyalot)</a></h2>
			</div>
		</div>
		<div id="map"></div>
		<div class="container">
			<?php printTitle('data'); ?> 
			<p>
			A visualization of the frequency and location of instagram photos tagged with <a href="http://instagram.com/aokhalifax" target="_blank">#aokhalifax</a>, a grass roots initiative to brighten the perspective of citizens and tourists: the goal being to promote positivity in Halifax.
			</p>
			<?php printTitle('options'); ?> 
			<ul>
				<li><b>size</b>: in meters (default: 30km)</li>
				<li><b>opacity</b>: in percent/100 (default: 1)</li>
				<li><b>gradientTexture</b>: url-to-texture-image (default: false)</li>
				<li><b>alphaRange</b>: change transparency in heatmap (default: 1)</li>
				<li><b>autoresize</b>: resize heatmap when map size changes (default: false)</li>
			</ul>
			<?php printTitle('usage'); ?> 
			<pre class="brush: js;">
	var baseURL = 'http://{s}.tile.cloudmade.com/{API}/{map_style}/256/{z}/{x}/{y}.png';
    
	var base = L.tileLayer(baseURL, { 
		API: your-api, 
		map_style: '44094' 
		});
    
	//Halifax, Nova Scotia
	var map = L.map('map', {layers: [base]}).setView([44.65, -63.57], 12);
    
	L.control.scale().addTo(map);
	
	//custom size for this example
	var heatmap = new L.TileLayer.WebGLHeatMap({size: 1000}); 
	
	// dataPoints is an array of arrays: [[lat, lng, intensity]...]
	var dataPoints = [[44.6674, -63.5703, 37], [44.6826, -63.7552, 34], [44.6325, -63.5852, 41], [44.6467, -63.4696, 67], [44.6804, -63.487, 64], [44.6622, -63.5364, 40], [44.603, - 63.743, 52] ...];
	for (var i = 0, len = dataPoints.length; i < len; i++) {
		var point = dataPoints[i];
		heatmap.addDataPoint(point[0],
			 point[1],
			 point[2]);
	}
	// alternatively, you can skip the for loop and add the whole dataset with heatmap.setData(dataPoints)
	
	map.addLayer(heatmap);</pre>
		</div>
		<div class="last container">
			<div id="footer">
			</div>
		</div>
	<script src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>
	<script type="text/javascript" src="js/shCore.js"></script>
	<script type="text/javascript" src="js/shBrushJScript.js"></script>
	
	<script type="text/javascript" src="js/webgl-heatmap.js"></script>
	<script type="text/javascript" src="js/webgl-heatmap-leaflet.js"></script>
	
	<script type="text/javascript">
	
	var baseURL = 'http://{s}.tile.cloudmade.com/{API}/{map_style}/256/{z}/{x}/{y}.png';
    
	var base = L.tileLayer(baseURL, { 
		API: '9315dcdc627b4feab430d377cd7cb978', 
		map_style: '1714' 
		});
    
	var map = L.map('map', {layers: [base]}).setView([44.68, -63.62], 12);
	map.scrollWheelZoom.disable();
    
	L.control.scale().addTo(map);
	
	
	//custom size for this example, and autoresize because map style has a percentage width
	var heatmap = new L.TileLayer.WebGLHeatMap({size: 1000}); 
	
	var dataPoints = [[50.880509986,-114.081560859],[50.880509986,-114.081560859],[50.880509986,-114.081560859],[44.53666687,-64.243164062],[44.639999389,-63.613998413],[44.676998138,-63.612499237],[44.679332733,-63.610500335],[50.970165252,-114.06916809],[34.104833333,-118.323],[50.579812463,-113.872800754],[51.055080414,-114.056716919],[44.648111204,-63.577139396],[44.642322778,-63.579243422],[44.643284609,-63.568868637],[44.64246,-63.578947],[44.718542104,-63.683588477],[44.718418471,-63.683593422],[44.718461344,-63.683637427],[44.718412771,-63.683782686],[44.718390978,-63.683674224],[44.718426894,-63.683400638],[44.718389102,-63.683563615],[44.643199507,-63.568366686],[44.718326605,-63.683847729],[44.7157814,-63.686402518],[44.718411484,-63.683636892],[44.718421013,-63.683612197],[44.718408703,-63.683583046],[44.718479198,-63.683512285],[44.718442462,-63.683621787],[44.70944854,-63.693567955],[44.718409395,-63.683602933],[44.718338801,-63.684254335],[44.718401488,-63.683540924],[44.718386997,-63.683626363],[44.718386997,-63.683626363],[44.718386997,-63.683626363],[44.717759553,-63.677263503],[44.642686,-63.578319],[44.718392151,-63.683523433],[44.718386997,-63.683626363],[44.718355229,-63.683762904],[44.718500027,-63.683851836],[44.718399905,-63.683797438],[44.718426224,-63.683320424],[44.647744146,-63.575160526],[44.642261709,-63.579683304],[44.649856,-63.586578],[44.647437,-63.580284],[44.718402168,-63.683638014],[44.718503631,-63.68352226],[44.718453507,-63.683740692],[44.718406694,-63.683453947],[44.718592538,-63.683768395],[44.718500529,-63.68364891],[44.718374717,-63.683847142],[44.718296221,-63.683787212],[44.718322533,-63.683521553],[44.718461344,-63.683620161],[44.718429676,-63.683640406],[44.71843339,-63.683663914],[44.718477647,-63.683813028],[44.718398396,-63.683542209],[44.718504084,-63.683465428],[44.718575212,-63.683621166],[44.718387784,-63.683589918],[44.718244917,-63.683892581],[44.718385838,-63.683624545],[44.718397606,-63.683539988],[44.718408668,-63.683616944],[44.718401751,-63.683572637],[44.718407164,-63.683572267],[44.718424391,-63.683666915],[44.718339513,-63.683889806],[44.718404213,-63.683593903],[44.718376712,-63.683603459],[44.718365334,-63.683625158],[44.718406172,-63.683623469],[44.718357136,-63.683653095],[44.71841303,-63.683625434],[44.718367131,-63.683636757],[44.718337501,-63.683804059],[44.718377546,-63.683478126],[44.718491649,-63.68370368],[44.718393032,-63.683595266],[44.718385449,-63.683592853]];
	
	for (var i = 0, len = dataPoints.length; i < len; i++) {
		var point = dataPoints[i];
		heatmap.addDataPoint(point[0],
			 point[1],
			 50);
	}

	<?php
	if (false) {
	?>
	
	//just a bunch of random points
	var dataPoints = [[44.6674, -63.5703, 37], [44.6826, -63.7552, 34], [44.6325, -63.5852, 41], [44.6467, -63.4696, 67], [44.6804, -63.487, 64], [44.6622, -63.5364, 40], [44.603, - 63.743, 52], [44.6562, -63.4586, 68], [44.6161, -63.4819, 48], [44.6345, -63.5628, 53], [44.6677, -63.5204, 57], [44.6545, -63.6977, 31], [44.6122, -63.4633, 64 ], [44.6852, -63.6793, 43], [44.6441, -63.5197, 48], [44.6164, -63.6273, 44], [44.6091, -63.5341, 51], [44.6392, -63.4804, 55], [44.6295, -63.5672, 30], [44.6848, -63.7755, 65], [44.6661, -63.5976, 65], [44.6669, -63.4765, 47], [44.6078, -63.6656, 67], [44.6774, -63.5169, 61], [44.6407, -63.5936, 39], [44.6158, -63.7763, 43], [44.6755, -63.5518, 47], [44.6458, -63.5582, 39], [44.6479, -63.7909, 58 ], [44.6206, -63.6247, 33], [44.64, -63.4983, 56], [44.6401, -63.5283, 41], [44.6008, -63.4604, 62], [44.6477, -63.7454, 46], [44.6843, -63.6324, 69], [44.6883,  -63.6399, 36], [44.631, -63.6822, 64], [44.6363, -63.6901, 44], [44.62, -63.4554, 47], [44.6744, -63.5796, 53], [44.6447, -63.7185, 65], [44.6474, -63.6926, 31 ], [44.6566, -63.6327, 34], [44.616, -63.7602, 39], [44.6891, -63.7194, 38], [44.6758, -63.471, 39], [44.6739, -63.4932, 51], [44.643, -63.5735, 38], [44.639, - 63.6901, 40], [44.6182, -63.6765, 65], [44.6022, -63.4304, 68], [44.6507, -63.6467, 68], [44.6348, -63.482, 36], [44.6775, -63.689, 39], [44.6078, -63.7407, 63] , [44.666, -63.4613, 37], [44.6187, -63.6358, 38], [44.6695, -63.6409, 56], [44.6634, -63.6363, 38], [44.6496, -63.7462, 32], [44.602, -63.6371, 49], [44.6605, -63.6409, 62], [44.6168, -63.7207, 69], [44.6319, -63.427, 59], [44.6154, -63.5784, 64], [44.6437, -63.5361, 37], [44.6145, -63.6595, 49], [44.669, -63.483, 66] , [44.6506, -63.6078, 30], [44.6639, -63.6699, 41], [44.6002, -63.5975, 48], [44.6357, -63.7315, 65], [44.6856, -63.7138, 46], [44.6843, -63.7007, 62], [44.6131 , -63.6055, 40], [44.6883, -63.6994, 46], [44.6517, -63.484, 42], [44.6622, -63.6969, 48], [44.6804, -63.6053, 39], [44.636, -63.5044, 35], [44.6868, -63.7189, 56], [44.6109, -63.5785, 35], [44.6014, -63.5408, 58], [44.6331, -63.4619, 38], [44.6817, -63.4463, 60], [44.67, -63.58, 69], [44.6411, -63.5277, 51], [44.6265, -63.6783, 43], [44.675, -63.4273, 46], [44.6058, -63.6508, 45], [44.6838, -63.4976, 41], [44.6603, -63.5285, 51], [44.6661, -63.7819, 65], [44.628, -63.4353, 34], [44.6737, -63.7137, 39], [44.6437, -63.7579, 34], [44.6301, -63.5475, 65], [ 44.6022, -63.6508, 59], [44.6738, -63.4369, 43], [44.6117, -63.7897, 51], [44.6354, -63.7382, 61], [44.6843, -63.4865, 64], [44.6477, -63.4344, 38], [44.6314, - 63.4796, 40], [44.6682, -63.6416, 57], [44.6363, -63.4511, 53], [44.6701, -63.6554, 33], [44.6648, -63.4873, 33], [44.6548, -63.6678, 45], [44.6553, -63.6242, 49], [44.6706, -63.6208, 61], [44.6149, -63.4923, 37], [44.6345, -63.7482, 38], [ 44.6091, -63.6979, 56], [44.6059, -63.744, 52], [44.6564, -63.7584, 55], [44.6781, -63.5269, 31], [44.6473, -63.4876, 33], [44.6124, -63.443, 68], [44.6651, -63.6665, 50], [44.6705, -63.489, 48], [44.6893, -63.6674, 63], [44.6019, -63.682, 57], [44.6633, -63.4833, 69], [44.6618, -63.6236, 41], [44.6793, -63.448, 43], [ 44.6414, -63.5798, 38], [44.6442, -63.6018, 34], [44.6373, -63.7988, 42], [44.6815, -63.6836, 36], [44.6697, -63.6279, 66], [44.6811, -63.4846, 30], [44.6503, - 63.4443, 32], [44.6723, -63.5588, 52], [44.6448, -63.7025, 52], [44.6424, -63.5415, 39], [44.6665, -63.7578, 56], [44.6672, -63.4674, 64], [44.6064, -63.4659, 48], [44.6893, -63.6704, 47], [44.6027, -63.7645, 67], [44.6095, -63.7305, 41], [ 44.6762, -63.6774, 65], [44.6036, -63.4893, 36], [44.6711, -63.7864, 40], [44.6368, -63.4604, 53], [44.6328, -63.5985, 33], [44.6087, -63.4857, 34], [44.602, -63.7064, 33], [44.6802, -63.5359, 46], [44.6704, -63.4567, 44], [44.6538, -63.7165, 36], [44.6317, -63.6907, 48], [44.6525, -63.4327, 34], [44.6024, -63.648, 65] , [44.6118, -63.7486, 52], [44.621, -63.5695, 66], [44.6106, -63.6682, 39], [44.6044, -63.6295, 34], [44.6543, -63.5569, 64], [44.6811, -63.7417, 59], [44.6626,  -63.6898, 42], [44.6773, -63.5313, 64], [44.6023, -63.7645, 48], [44.6239, -63.4234, 59], [44.6391, -63.4846, 48], [44.6294, -63.634, 59], [44.6169, -63.7512, 48], [44.6409, -63.4622, 57], [44.6732, -63.5182, 68], [44.6622, -63.5825, 42], [44.6444, -63.4818, 35], [44.6486, -63.5529, 53], [44.6215, -63.4958, 41], [44.6174, -63.4903, 40], [44.6205, -63.676, 36], [44.6183, -63.5166, 48], [44.6306, - 63.5126, 49], [44.6742, -63.5145, 37], [44.6213, -63.5758, 62], [44.6624, -63.6011, 32], [44.6017, -63.4645, 61], [44.6023, -63.7839, 58], [44.6721, -63.6917, 33], [44.6893, -63.4549, 38], [44.6435, -63.7343, 66], [44.6397, -63.6395, 44], [ 44.644, -63.597, 66], [44.6451, -63.657, 33], [44.6807, -63.6768, 59], [44.669, -63.7791, 56], [44.6523, -63.6702, 53], [44.6824, -63.4732, 37], [44.6861, -63.4516, 57], [44.6257, -63.482, 41], [44.6326, -63.6919, 42], [44.6707, -63.6688, 40], [44.641, -63.565, 57], [44.6012, -63.5285, 45], [44.6705, -63.7293, 49], [44.6598, -63.7527, 34], [44.6552, -63.4708, 52], [44.6245, -63.5043, 61], [44.6801 , -63.5922, 37], [44.6119, -63.7914, 42], [44.6182, -63.4795, 40], [44.6081, -63.6346, 56], [44.6502, -63.5531, 37], [44.6353, -63.5466, 40], [44.6097, -63.4648 , 32], [44.6344, -63.5852, 48], [44.6833, -63.4984, 44], [44.6686, -63.6806, 32] , [44.6157, -63.4998, 32], [44.651, -63.4778, 67], [44.6858, -63.7882, 41], [44.6503, -63.6701, 35], [44.616, -63.4827, 65], [44.6463, -63.4718, 31], [44.6455, -63.7672, 31], [44.6181, -63.5123, 40], [44.6722, -63.4512, 49], [44.6027, -63.6117, 68], [44.6062, -63.6105, 67], [44.6583, -63.676, 48], [44.6775, -63.5718, 40], [44.6231, -63.4949, 43], [44.6236, -63.4838, 44], [44.6773, -63.5627, 52], [ 44.6535, -63.5171, 47], [44.6481, -63.6223, 44], [44.6385, -63.6444, 58], [44.6884, -63.616, 35], [44.6435, -63.4409, 49], [44.6653, -63.425, 64], [44.6899, -63.5109, 36], [44.628, -63.7044, 33], [44.6037, -63.5371, 32], [44.6799, -63.7165,  34], [44.6128, -63.5155, 64], [44.6514, -63.6262, 57], [44.6714, -63.796, 67], [44.6213, -63.6407, 61], [44.6787, -63.4782, 30], [44.637, -63.6848, 43], [44.6805, -63.703, 49], [44.6078, -63.6076, 65], [44.6769, -63.7547, 40], [44.6103, -63.7946, 34], [44.6798, -63.4429, 37], [44.6497, -63.6671, 54], [44.62, -63.7008,  31], [44.6409, -63.7081, 38], [44.6629, -63.4923, 34], [44.6692, -63.4684, 53],  [44.6684, -63.5732, 38], [44.6168, -63.6774, 42], [44.6587, -63.4596, 31], [44.6695, -63.6059, 63], [44.6123, -63.5962, 47], [44.6019, -63.728, 64], [44.6082, -63.5893, 56], [44.6502, -63.7931, 39], [44.6445, -63.5856, 62], [44.6729, -63.4746, 39], [44.6348, -63.4768, 46], [44.6457, -63.5655, 68], [44.6892, -63.5838, 38], [44.6854, -63.4287, 66], [44.6132, -63.6071, 56], [44.6347, -63.6183, 43], [44.6797, -63.5003, 68], [44.6669, -63.6622, 69], [44.6191, -63.5238, 34], [44.6428, -63.7218, 36], [44.6156, -63.6534, 48], [44.6159, -63.4916, 60], [44.6382, -63.7558, 58], [44.6225, -63.7635, 54], [44.6585, -63.7093, 55], [44.6501, -63.5352, 69], [44.684, -63.5363, 58], [44.6038, -63.5063, 35], [44.626, -63.5547, 60 ], [44.6189, -63.7059, 54], [44.6144, -63.5342, 66], [44.6522, -63.691, 66], [44.6205, -63.7538, 59], [44.6675, -63.7421, 43], [44.6354, -63.5173, 48], [44.6254 , -63.4549, 60], [44.6243, -63.4393, 69], [44.607, -63.6979, 46], [44.6068, -63.4749, 34], [44.6369, -63.6704, 58], [44.6882, -63.5862, 53], [44.6133, -63.6158,  67], [44.6514, -63.6857, 62], [44.6844, -63.5677, 50], [44.6614, -63.7802, 47],  [44.6228, -63.6164, 30], [44.6338, -63.6529, 39], [44.6896, -63.4288, 43], [44.6142, -63.6878, 69], [44.6144, -63.7777, 48], [44.6772, -63.5444, 58], [44.6607,  -63.7695, 69], [44.602, -63.5139, 37], [44.6673, -63.6732, 39], [44.6192, -63.669, 63], [44.6015, -63.6762, 47], [44.6821, -63.7109, 65], [44.6716, -63.6456, 67], [44.6486, -63.7457, 59], [44.6504, -63.7201, 67], [44.653, -63.6552, 60], [44.6776, -63.7754, 56], [44.6853, -63.7056, 32], [44.6706, -63.4932, 43], [44.667 , -63.6047, 65], [44.6144, -63.5647, 37], [44.6278, -63.548, 47], [44.6001, -63.7776, 60], [44.6345, -63.6976, 57], [44.6088, -63.4936, 67], [44.6586, -63.4633,  36], [44.6403, -63.4455, 40], [44.6536, -63.4888, 47], [44.6409, -63.775, 47], [44.6622, -63.4682, 61], [44.6294, -63.5407, 44], [44.642, -63.569, 49], [44.6574, -63.4262, 32], [44.6175, -63.555, 67], [44.612, -63.743, 41], [44.6697, -63.7648, 65], [44.6568, -63.7792, 44], [44.6881, -63.6948, 41], [44.6347, -63.4611, 38], [44.6338, -63.793, 47], [44.6075, -63.5548, 51], [44.6235, -63.5288, 60], [ 44.689, -63.4658, 51], [44.6045, -63.5924, 66], [44.6137, -63.5015, 59], [44.6026, -63.5041, 39], [44.6819, -63.5388, 49], [44.6486, -63.4841, 33], [44.6775, -63.5127, 63], [44.6422, -63.7872, 37], [44.6481, -63.7631, 65], [44.6489, -63.4871, 39], [44.6475, -63.7338, 37], [44.6621, -63.7066, 57], [44.682, -63.5285, 41] , [44.6782, -63.6452, 46], [44.6481, -63.5189, 65], [44.6564, -63.5992, 48], [44.684, -63.5934, 51], [44.6361, -63.5884, 39], [44.6022, -63.5444, 64], [44.6677,  -63.7599, 42], [44.6839, -63.594, 46], [44.6477, -63.5323, 59], [44.6488, -63.5763, 35], [44.62, -63.5561, 31], [44.6491, -63.7526, 56], [44.6059, -63.5787, 42 ], [44.6079, -63.7418, 56], [44.6782, -63.6836, 33], [44.6787, -63.4215, 30], [44.6596, -63.7484, 59], [44.6099, -63.5533, 56], [44.6139, -63.5592, 33], [44.6086, -63.4834, 38], [44.6778, -63.743, 37], [44.664, -63.5605, 43], [44.6059, -63.671, 62], [44.6412, -63.6227, 48], [44.6334, -63.7262, 38], [44.6746, -63.76, 64 ], [44.6812, -63.6835, 60], [44.6388, -63.7308, 40], [44.611, -63.5006, 41], [44.6314, -63.7825, 64], [44.6154, -63.6901, 59], [44.6016, -63.453, 49], [44.6547,  -63.7487, 60], [44.6586, -63.4781, 43], [44.6167, -63.5732, 62], [44.6151, -63.544, 55], [44.6094, -63.5079, 64], [44.6776, -63.4361, 59], [44.6332, -63.4363, 41], [44.6881, -63.5955, 43], [44.6657, -63.7991, 49], [44.651, -63.5941, 30], [ 44.6567, -63.4968, 38], [44.6419, -63.7721, 44], [44.6137, -63.5076, 32]];
	
	for (var i = 0, len = dataPoints.length; i < len; i++) {
		var point = dataPoints[i];
		heatmap.addDataPoint(point[0],
			 point[1],
			 point[2]);
	}
	
	<?php
	}
	?>
	
	/* 
	*  alternatively, if you have intensities set for each point, 
	*  as in above, you can skip the for loop and add the whole dataset 
	*  with heatmap.setData(dataPoints) 
	*/
	
	map.addLayer(heatmap);
	
	SyntaxHighlighter.all();
	
	</script>
	
	</body>
</html>
