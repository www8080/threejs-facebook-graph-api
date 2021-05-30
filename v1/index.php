<?php
// https://graph.facebook.com/v10.0/101200052181927/friends?access_token=783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk&pretty=1&limit=150&debug=all&fields=id,name,gender,first_name,birthday

ini_set('display_errors', 1);
error_reporting(E_ALL);

//GET Facebook Graph API Response--------------------------------------------------------------------------------------------------
$debug_mode   = 'all';
$app_ver      = 'v10.0';
$access_token = '783263342388998|dkQ1iavY-DR9E25zXOlKmU_6wpk';
$fields       = 'id,name,gender,first_name,birthday';
$user_id      = '101200052181927';
$pretty       = 1;
$limit        = 150;
$api_url      = 'https://graph.facebook.com/'.$app_ver.'/'.$user_id.'/friends?access_token='.$access_token.'&pretty='.$pretty.'&limit='.$limit.'&debug='.$debug_mode.'&fields='.$fields;


//Initiate CURL request
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $api_url);
    $result = curl_exec($ch);
    curl_close($ch);
}
catch(Exception $e){
    throw new Exception("Error",0,$e);
    exit;
}

if($result) {
  $obj      = json_decode($result, true);
  //$obj_arr  = array_values(json_decode($obj, true));

  //echo $obj->data[1]->first_name; 
  //dd($obj);

  // foreach ($obj as $key => $friend) {
  //   echo $friend['data']['first_name'];
  // }

  $x = 1;
  $y = 1;
  foreach($obj['data'] as $friend) {
    $friend_age    = @ageCalculator($friend['birthday']) ?? 'null';
    $friend_gender = ($friend['gender'] == "male") ? 'M' : 'F';
    $friend_arr[]  = $friend['first_name'] . ',' . $friend_age . ',' . $friend_gender . ',' . $x  . ',' .  $y;

    //echo $friend['first_name'] . "," . $friend_age . "," . $friend_gender . "<br>";
    //$x++;
  	$y++;
  }
  $friend_arr_js   = json_encode($friend_arr,JSON_PRETTY_PRINT);

  //dd($friend_arr);
  //dd($friend_arr_js);

  //echo "<br>Total Friend: " . sizeof($obj['data']);
}

//END Facebook Graph API Response--------------------------------------------------------------------------------------------------


//Functions --------------------------------------------------------------------------------------------------
function ageCalculator($dob){
    if(!empty($dob)){
        $birthdate = new DateTime($dob);
        $today   = new DateTime('today');
        $age = $birthdate->diff($today)->y;
        return $age;
    }else{
        return 0;
    }
}
//$dob = '01/01/2002';
//echo "Age: " . ageCalculator($dob);
// echo "Age: ". date_diff(date_create('01/07/2002'), date_create('today'))->y;

 function dd()
  {
      echo '<pre>';
      array_map(function($x) {var_dump($x);}, func_get_args());
      die;
  }

//END Function --------------------------------------------------------------------------------------------------
?>
<!DOCTYPE html>

<html>
	<head>
		<title>three.js css3d - periodic table</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="../files/main.css">
	</head>
	<body>

		<div id="info"><a href="https://threejs.org" target="_blank" rel="noopener">three.js - assigment</a> <br>Facebook Friend List</div>
		<div id="container"></div>
		<div id="menu">
			<button id="table">TABLE</button>
			<button id="sphere">SPHERE</button>
			<button id="helix">HELIX</button>
			<button id="grid">GRID</button>
			<button id="cylinder">CYLINDER</button>
		</div>

		<script type="module">

			import * as THREE from '../build/three.module.js';

			import { TWEEN } from '../examples/jsm/libs/tween.module.min.js';
			import { TrackballControls } from '../examples/jsm/controls/TrackballControls.js';
			import { CSS3DRenderer, CSS3DObject } from '../examples/jsm/renderers/CSS3DRenderer.js';


		    const table = [
		    	<?php
		    		$x 		 = 1;
  					$y 		 = 1;
  					$x_limit = 19;

		    		foreach($obj['data'] as $friend) {
					    $friend_age    = @ageCalculator($friend['birthday']) ?? 'null';
					    $friend_gender = ($friend['gender'] == "male") ? 'M' : 'F';

					    echo '"'.$friend['first_name'] . '","' . $friend_age . '","' . $friend_gender . '",'. $x  . ',' .  $y . ',';
					  	if ($x < $x_limit) {
					  		$x++;
					  	}
					  	if ($x == $x_limit) {
					  		//Reset $x, $y++ print to next line
					  		$x = 1;
					  		$y++;
					  	}
				  }
		    	?>
		    ];

		    const friend_gender = [
		    	<?php
		    		foreach($obj['data'] as $friend) {
				    	$friend_gender = ($friend['gender'] == "male") ? 'M' : 'F';
				    	echo '"' . $friend_gender . '",';
					}
		    	?>
		    ];


			//Debug Reference
			const table2 = [
				"Cindy", "Female", "28", 1, 1,
				"Michael", "Femlae", "21", 2, 1,
				"Li", "Lithium", "6.941", 1, 2,
				"Be", "Beryllium", "9.012182", 2, 2,
				"B", "Boron", "10.811", 3, 2,
			];
			// 	"C", "Carbon", "12.0107", 14, 2,
			// 	"N", "Nitrogen", "14.0067", 15, 2,
			// 	"O", "Oxygen", "15.9994", 16, 2,
			// 	"F", "Fluorine", "18.9984032", 17, 2,
			// 	"Ne", "Neon", "20.1797", 18, 2,
			// 	"Na", "Sodium", "22.98976...", 1, 3,
			// 	"Mg", "Magnesium", "24.305", 2, 3,
			// 	"Al", "Aluminium", "26.9815386", 13, 3,
			// 	"Si", "Silicon", "28.0855", 14, 3,
			// 	"P", "Phosphorus", "30.973762", 15, 3,
			// 	"S", "Sulfur", "32.065", 16, 3,
			// 	"Cl", "Chlorine", "35.453", 17, 3,
			// 	"Ar", "Argon", "39.948", 18, 3,
			// 	"K", "Potassium", "39.948", 1, 4,
			// 	"Ca", "Calcium", "40.078", 2, 4,
			// 	"Sc", "Scandium", "44.955912", 3, 4,
			// 	"Ti", "Titanium", "47.867", 4, 4,
			// 	"V", "Vanadium", "50.9415", 5, 4,
			// 	"Cr", "Chromium", "51.9961", 6, 4,
			// 	"Mn", "Manganese", "54.938045", 7, 4,
			// 	"Fe", "Iron", "55.845", 8, 4,
			// 	"Co", "Cobalt", "58.933195", 9, 4,
			// 	"Ni", "Nickel", "58.6934", 10, 4,
			// 	"Cu", "Copper", "63.546", 11, 4,
			// 	"Zn", "Zinc", "65.38", 12, 4,
			// 	"Ga", "Gallium", "69.723", 13, 4,
			// 	"Ge", "Germanium", "72.63", 14, 4,
			// 	"As", "Arsenic", "74.9216", 15, 4,
			// 	"Se", "Selenium", "78.96", 16, 4,
			// 	"Br", "Bromine", "79.904", 17, 4,
			// 	"Kr", "Krypton", "83.798", 18, 4,
			// 	"Rb", "Rubidium", "85.4678", 1, 5,
			// 	"Sr", "Strontium", "87.62", 2, 5,
			// 	"Y", "Yttrium", "88.90585", 3, 5,
			// 	"Zr", "Zirconium", "91.224", 4, 5,
			// 	"Nb", "Niobium", "92.90628", 5, 5,
			// 	"Mo", "Molybdenum", "95.96", 6, 5,
			// 	"Tc", "Technetium", "(98)", 7, 5,
			// 	"Ru", "Ruthenium", "101.07", 8, 5,
			// 	"Rh", "Rhodium", "102.9055", 9, 5,
			// 	"Pd", "Palladium", "106.42", 10, 5,
			// 	"Ag", "Silver", "107.8682", 11, 5,
			// 	"Cd", "Cadmium", "112.411", 12, 5,
			// 	"In", "Indium", "114.818", 13, 5,
			// 	"Sn", "Tin", "118.71", 14, 5,
			// 	"Sb", "Antimony", "121.76", 15, 5,
			// 	"Te", "Tellurium", "127.6", 16, 5,
			// 	"I", "Iodine", "126.90447", 17, 5,
			// 	"Xe", "Xenon", "131.293", 18, 5,
			// 	"Cs", "Caesium", "132.9054", 1, 6,
			// 	"Ba", "Barium", "132.9054", 2, 6,
			// 	"La", "Lanthanum", "138.90547", 4, 9,
			// 	"Ce", "Cerium", "140.116", 5, 9,
			// 	"Pr", "Praseodymium", "140.90765", 6, 9,
			// 	"Nd", "Neodymium", "144.242", 7, 9,
			// 	"Pm", "Promethium", "(145)", 8, 9,
			// 	"Sm", "Samarium", "150.36", 9, 9,
			// 	"Eu", "Europium", "151.964", 10, 9,
			// 	"Gd", "Gadolinium", "157.25", 11, 9,
			// 	"Tb", "Terbium", "158.92535", 12, 9,
			// 	"Dy", "Dysprosium", "162.5", 13, 9,
			// 	"Ho", "Holmium", "164.93032", 14, 9,
			// 	"Er", "Erbium", "167.259", 15, 9,
			// 	"Tm", "Thulium", "168.93421", 16, 9,
			// 	"Yb", "Ytterbium", "173.054", 17, 9,
			// 	"Lu", "Lutetium", "174.9668", 18, 9,
			// 	"Hf", "Hafnium", "178.49", 4, 6,
			// 	"Ta", "Tantalum", "180.94788", 5, 6,
			// 	"W", "Tungsten", "183.84", 6, 6,
			// 	"Re", "Rhenium", "186.207", 7, 6,
			// 	"Os", "Osmium", "190.23", 8, 6,
			// 	"Ir", "Iridium", "192.217", 9, 6,
			// 	"Pt", "Platinum", "195.084", 10, 6,
			// 	"Au", "Gold", "196.966569", 11, 6,
			// 	"Hg", "Mercury", "200.59", 12, 6,
			// 	"Tl", "Thallium", "204.3833", 13, 6,
			// 	"Pb", "Lead", "207.2", 14, 6,
			// 	"Bi", "Bismuth", "208.9804", 15, 6,
			// 	"Po", "Polonium", "(209)", 16, 6,
			// 	"At", "Astatine", "(210)", 17, 6,
			// 	"Rn", "Radon", "(222)", 18, 6,
			// 	"Fr", "Francium", "(223)", 1, 7,
			// 	"Ra", "Radium", "(226)", 2, 7,
			// 	"Ac", "Actinium", "(227)", 4, 10,
			// 	"Th", "Thorium", "232.03806", 5, 10,
			// 	"Pa", "Protactinium", "231.0588", 6, 10,
			// 	"U", "Uranium", "238.02891", 7, 10,
			// 	"Np", "Neptunium", "(237)", 8, 10,
			// 	"Pu", "Plutonium", "(244)", 9, 10,
			// 	"Am", "Americium", "(243)", 10, 10,
			// 	"Cm", "Curium", "(247)", 11, 10,
			// 	"Bk", "Berkelium", "(247)", 12, 10,
			// 	"Cf", "Californium", "(251)", 13, 10,
			// 	"Es", "Einstenium", "(252)", 14, 10,
			// 	"Fm", "Fermium", "(257)", 15, 10,
			// 	"Md", "Mendelevium", "(258)", 16, 10,
			// 	"No", "Nobelium", "(259)", 17, 10,
			// 	"Lr", "Lawrencium", "(262)", 18, 10,
			// 	"Rf", "Rutherfordium", "(267)", 4, 7,
			// 	"Db", "Dubnium", "(268)", 5, 7,
			// 	"Sg", "Seaborgium", "(271)", 6, 7,
			// 	"Bh", "Bohrium", "(272)", 7, 7,
			// 	"Hs", "Hassium", "(270)", 8, 7,
			// 	"Mt", "Meitnerium", "(276)", 9, 7,
			// 	"Ds", "Darmstadium", "(281)", 10, 7,
			// 	"Rg", "Roentgenium", "(280)", 11, 7,
			// 	"Cn", "Copernicium", "(285)", 12, 7,
			// 	"Nh", "Nihonium", "(286)", 13, 7,
			// 	"Fl", "Flerovium", "(289)", 14, 7,
			// 	"Mc", "Moscovium", "(290)", 15, 7,
			// 	"Lv", "Livermorium", "(293)", 16, 7,
			// 	"Ts", "Tennessine", "(294)", 17, 7,
			// 	"Og", "Oganesson", "(294)", 18, 7
			// ];

			let camera, scene, renderer;
			let controls;

			const objects = [];
			const targets = { table: [], table2: [], sphere: [], helix: [], grid: [], cylinder: [] };

			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 3000;

				scene = new THREE.Scene();

				// table
				//alert(friend_gender);
				for ( let i = 0, j = 0; i < table.length; i += 5, j++ ) {

					const element = document.createElement( 'div' );
					element.className = 'element';
					//pink
					if (friend_gender[j] == "F")
						element.style.backgroundColor = 'rgba(255,20,147)';
						//Backgroup Transparent
						//element.style.backgroundColor = 'rgba(255,105,180,' + ( 0.4690487523459153 * 0.5 + 0.25 ) + ')';
					//blue
					if (friend_gender[j] == "M")
						element.style.backgroundColor = 'rgba(28,28,240)';
						//Backgroup Transparent
						// element.style.backgroundColor = 'rgba(0,127,255,' + ( 0.3690487523459153 * 0.5 + 0.25 ) + ')';
						//element.style.backgroundColor = '#FFC0CB';


					const number = document.createElement( 'div' );
					number.className = 'number';
					number.textContent = ( i / 5 ) + 1;
					element.appendChild( number );

					const symbol = document.createElement( 'div' );
					symbol.className = 'symbol';
					symbol.textContent = table[ i ];
					element.appendChild( symbol );

					const details = document.createElement( 'div' );
					details.className = 'details';
					details.innerHTML = table[ i + 1 ] + '<br>' + table[ i + 2 ];
					element.appendChild( details );

					const objectCSS = new CSS3DObject( element );
					objectCSS.position.x = Math.random() * 4000 - 2000;
					objectCSS.position.y = Math.random() * 4000 - 2000;
					objectCSS.position.z = Math.random() * 4000 - 2000;
					scene.add( objectCSS );

					objects.push( objectCSS );

					const object = new THREE.Object3D();
					object.position.x = ( table[ i + 3 ] * 140 ) - 1330;
					object.position.y = - ( table[ i + 4 ] * 180 ) + 990;

					const object3 = new THREE.Object3D();
					object3.position.x = ( table[ i + 3 ] * 140 ) - 1330;
					object3.position.y = - ( table[ i + 4 ] * 180 ) + 990;

					targets.table.push( object );
					//targets.cylinder.push( object3 );

				}

				// sphere

				const vector = new THREE.Vector3();

				for ( let i = 0, l = objects.length; i < l; i ++ ) {

					const phi = Math.acos( - 1 + ( 2 * i ) / l );
					const theta = Math.sqrt( l * Math.PI ) * phi;

					const object = new THREE.Object3D();

					object.position.setFromSphericalCoords( 800, phi, theta );

					vector.copy( object.position ).multiplyScalar( 2 );

					object.lookAt( vector );

					targets.sphere.push( object );

				}

				// helix

				for ( let i = 0, l = objects.length; i < l; i ++ ) {

					const theta = i * 0.175 + Math.PI;
					const y = - ( i * 8 ) + 450;

					const object = new THREE.Object3D();

					object.position.setFromCylindricalCoords( 900, theta, y );

					vector.x = object.position.x * 2;
					vector.y = object.position.y;
					vector.z = object.position.z * 2;

					object.lookAt( vector );

					targets.helix.push( object );

				}

				// grid

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = new THREE.Object3D();

					object.position.x = ( ( i % 5 ) * 400 ) - 800;
					object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
					object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

					targets.grid.push( object );

				}


				// cylinder

				//console.log(targets.table);
				//console.log(table.length);

				//var vector = new THREE.Vector3();
				var lookAt = new THREE.Vector3();
				var lookAtScale = new THREE.Vector3(1.1, 1, 1.1);
				var itemH = 180;
				var itemW = 140;
				var itemsPerRound = 15;
				var rounds = Math.ceil(table.length / 5 / itemsPerRound);

				console.log("Table length, Rounds = "+table.length, rounds);

				var sectorStep = Math.PI * 2 / itemsPerRound;
				var mainRadius = 390;
				 
				for (let i = 0, j = 0; i < table.length; i+=5, j++) {
					let ii = i / 5;
				  	let round = Math.floor(ii / itemsPerRound);
				    let sector = (ii % itemsPerRound) * sectorStep;
				    
				    //console.log(i, i / itemsPerRound, i % itemsPerRound)
				    
				    let h = (itemH * round) - (itemH * (rounds * 0.5));
				    //console.log("mainRadius:"+mainRadius+"--sector:"+sector+"--h:"+h);
				    let object = new THREE.Object3D();
				    object.position.setFromCylindricalCoords(mainRadius, sector, h);
				    lookAt.copy(object.position);
				    lookAt.multiply(lookAtScale);
				    object.lookAt(lookAt);
				    targets.cylinder.push(object);


				    //TESTING --------------------------
				    //TOP
				    let vector1 = new THREE.Vector3();
				    let vector2 = new THREE.Vector3();
				    let vector3 = new THREE.Vector3();
				    let vector4 = new THREE.Vector3();
				   	if (i > 400) { //change to percentage
					 	const theta2 = ii * 0.675 + Math.PI;
						const y2 = - ( ii * 8 ) + 450;

						const object2 = new THREE.Object3D();
						const object3 = new THREE.Object3D();
						const object4 = new THREE.Object3D();
						const object5 = new THREE.Object3D();
						console.log(theta2);

						object2.position.setFromCylindricalCoords( 305, theta2, 280 );
												
						vector1.x = object2.position.x * 2;
						vector1.y = object2.position.y * 320;
						vector1.z = object2.position.z * 2;

						object2.lookAt( vector1 );
						targets.cylinder.push( object2 );

						//Top Inner
						if (i > 430) {
							const theta3 = ii * 1.999 + Math.PI;
							const y2 = - ( ii * 8 ) + 450;
							object3.position.setFromCylindricalCoords( 125, theta3, 280 );

							vector2.x = object3.position.x * 2;
							vector2.y = object3.position.y * 320;
							vector2.z = object3.position.z * 2;

							object3.lookAt( vector2 );
							targets.cylinder.push( object3 );
						}


						//Bottom
						if (i > 410 || i < 450) {
							const theta4 = ii * 0.675 + Math.PI;
							const y2 = - ( ii * 8 ) + 450;
							object4.position.setFromCylindricalCoords( 305, theta4, -800 );

							vector3.x = object4.position.x * 2;
							vector3.y = object4.position.y * 320;
							vector3.z = object4.position.z * 2;

							object4.lookAt( vector3 );
							targets.cylinder.push( object4 );
						}

						//Bottom Inner
						if (i > 430) {
							const theta5 = ii * 1.999 + Math.PI;
							const y2 = - ( ii * 8 ) + 450;
							object5.position.setFromCylindricalCoords( 125, theta5, -800 );

							vector4.x = object5.position.x * 2;
							vector4.y = object5.position.y * 320;
							vector4.z = object5.position.z * 2;

							object5.lookAt( vector4 );
							targets.cylinder.push( object5 );
						}
					}

					

				  }
				  //console.log(table2.length);
				  //Cylinder top-bottom
				//  for (let i = 0; i < table2.length; i++) {
				//   	const theta2 = i * 0.175 + Math.PI;
				// 	const y2 = - ( i * 8 ) + 450;

				// 	const object = new THREE.Object3D();

				// 	object.position.setFromCylindricalCoords( 900, theta2, y2 );

				// 	vector.x = object.position.x * 2;
				// 	vector.y = object.position.y;
				// 	vector.z = object.position.z * 2;

				// 	object.lookAt( vector );

				// 	targets.cylinder.push( object );
				// }

				// for ( let i = 0; i < objects.length; i ++ ) {

				// 	const object = new THREE.Object3D();

				// 	object.position.x = ( ( i % 5 ) * 400 ) - 800;
				// 	object.position.y = ( - ( Math.floor( i / 5 ) % 5 ) * 400 ) + 800;
				// 	object.position.z = ( Math.floor( i / 25 ) ) * 1000 - 2000;

				// 	targets.cylinder.push( object );

				// }


				//Renderer

				renderer = new CSS3DRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.getElementById( 'container' ).appendChild( renderer.domElement );

				//

				controls = new TrackballControls( camera, renderer.domElement );
				controls.minDistance = 500;
				controls.maxDistance = 6000;
				controls.addEventListener( 'change', render );

				const buttonTable = document.getElementById( 'table' );
				buttonTable.addEventListener( 'click', function () {

					transform( targets.table, 2000 );

				} );

				const buttonSphere = document.getElementById( 'sphere' );
				buttonSphere.addEventListener( 'click', function () {

					transform( targets.sphere, 2000 );

				} );

				const buttonHelix = document.getElementById( 'helix' );
				buttonHelix.addEventListener( 'click', function () {

					transform( targets.helix, 2000 );

				} );

				const buttonGrid = document.getElementById( 'grid' );
				buttonGrid.addEventListener( 'click', function () {

					transform( targets.grid, 2000 );

				} );

				var buttonCylinder = document.getElementById('cylinder');
				buttonCylinder.addEventListener('click', function() {

				    transform(targets.cylinder, 2000);

				}, false);

				//Targets to show upon script load
				transform( targets.table, 2000 );
				//transform( targets.cylinder, 2000 );

				//

				window.addEventListener( 'resize', onWindowResize );

			}

			function transform( targets, duration ) {

				TWEEN.removeAll();

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = objects[ i ];
					const target = targets[ i ];

					new TWEEN.Tween( object.position )
						.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

					new TWEEN.Tween( object.rotation )
						.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

				}

				new TWEEN.Tween( this )
					.to( {}, duration * 2 )
					.onUpdate( render )
					.start();

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			function animate() {

				requestAnimationFrame( animate );

				TWEEN.update();

				controls.update();

			}

			function render() {

				renderer.render( scene, camera );

			}

		</script>
	</body>
</html>