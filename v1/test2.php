import * as THREE from 'https://threejs.org/build/three.module.js';

import {
  TWEEN
} from 'https://threejs.org/examples/jsm/libs/tween.module.min.js';
import {
  TrackballControls
} from 'https://threejs.org/examples/jsm/controls/TrackballControls.js';
import {
  CSS3DRenderer,
  CSS3DObject
} from 'https://threejs.org/examples/jsm/renderers/CSS3DRenderer.js';

var camera, scene, renderer;
var controls;

var objects = [];
var targets = {
  table: [],
  cylinder: []
};

init();
animate();

function init() {

  camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 1, 10000);
  camera.position.z = 3000;

  scene = new THREE.Scene();

  // table
  for (var i = 0; i < table.length; i += 5) {

    var element = document.createElement('div');
    element.className = 'element';
    element.style.backgroundColor = 'rgba(0,127,127,' + (Math.random() * 0.5 + 0.25) + ')';

    var number = document.createElement('div');
    number.className = 'number';
    number.textContent = (i / 5) + 1;
    element.appendChild(number);

    var symbol = document.createElement('div');
    symbol.className = 'symbol';
    symbol.textContent = table[i];
    element.appendChild(symbol);

    var details = document.createElement('div');
    details.className = 'details';
    details.innerHTML = table[i + 1] + '<br>' + table[i + 2];
    element.appendChild(details);

    var object = new CSS3DObject(element);
    object.position.x = Math.random() * 4000 - 2000;
    object.position.y = Math.random() * 4000 - 2000;
    object.position.z = Math.random() * 4000 - 2000;
    scene.add(object);

    objects.push(object);

    //

    var object = new THREE.Object3D();
    object.position.x = (table[i + 3] * 140) - 1330;
    object.position.y = -(table[i + 4] * 180) + 990;

    targets.table.push(object);

  }
console.log(targets.table);
console.log(table.length);
  // cylinder

  var vector = new THREE.Vector3();
  var lookAt = new THREE.Vector3();
  var lookAtScale = new THREE.Vector3(1.1, 1, 1.1);
  var itemH = 180;
  var itemW = 140;
	var itemsPerRound = 20;
  var rounds = Math.ceil(table.length / 5 / itemsPerRound);
  console.log(table.length, rounds);
  var sectorStep = Math.PI * 2 / itemsPerRound;
  var mainRadius = 500;
  
  for (let i = 0; i < table.length; i+=5) {
  	let ii = i / 5;
  	let round = Math.floor(ii / itemsPerRound);
    let sector = (ii % itemsPerRound) * sectorStep;
    
    //console.log(i, i / itemsPerRound, i % itemsPerRound)
    
    let h = (itemH * round) - (itemH * (rounds * 0.5));
    let object = new THREE.Object3D();
    object.position.setFromCylindricalCoords(mainRadius, sector, h);
    lookAt.copy(object.position);
    lookAt.multiply(lookAtScale);
    object.lookAt(lookAt);
    targets.cylinder.push(object);
  }

  renderer = new CSS3DRenderer();
  renderer.setSize(window.innerWidth, window.innerHeight);
  document.getElementById('container').appendChild(renderer.domElement);

  //

  controls = new TrackballControls(camera, renderer.domElement);
  controls.minDistance = 500;
  controls.maxDistance = 6000;
  controls.addEventListener('change', render);

  var button = document.getElementById('table');
  button.addEventListener('click', function() {

    transform(targets.table, 2000);

  }, false);

  var button = document.getElementById('cylinder');
  button.addEventListener('click', function() {

    transform(targets.cylinder, 2000);

  }, false);

  transform(targets.table, 2000);

  //

  window.addEventListener('resize', onWindowResize, false);

}

function transform(targets, duration) {

  TWEEN.removeAll();

  for (var i = 0; i < objects.length; i++) {

    var object = objects[i];
    var target = targets[i];

    new TWEEN.Tween(object.position)
      .to({
      x: target.position.x,
      y: target.position.y,
      z: target.position.z
    }, Math.random() * duration + duration)
      .easing(TWEEN.Easing.Exponential.InOut)
      .start();

    new TWEEN.Tween(object.rotation)
      .to({
      x: target.rotation.x,
      y: target.rotation.y,
      z: target.rotation.z
    }, Math.random() * duration + duration)
      .easing(TWEEN.Easing.Exponential.InOut)
      .start();

  }

  new TWEEN.Tween(this)
    .to({}, duration * 2)
    .onUpdate(render)
    .start();

}

function onWindowResize() {

  camera.aspect = window.innerWidth / window.innerHeight;
  camera.updateProjectionMatrix();

  renderer.setSize(window.innerWidth, window.innerHeight);

  render();

}

function animate() {

  requestAnimationFrame(animate);

  TWEEN.update();

  controls.update();

}

function render() {

  renderer.render(scene, camera);

}
