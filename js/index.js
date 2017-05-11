$(document).ready(function() {
	if($('#canvas').size() > 0) {
		var w = $('#canvas').width();
		var h = $('#risultati').height();
		$('#canvas').attr('width', w);
		$('#canvas').attr('height', h);
		
		drawCanvas();
	}
});

function drawCanvas() {
	var w = $('#canvas').width();
	var h = $('#canvas').height();
	var min = w > h ? h : w;
	
	$('#canvas').drawLine({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x1: 5, y1: (h / 2) - 1,
		x2: w - 5, y2: (h / 2) - 1
	}).drawLine({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x1: (w / 2) - 1, y1: 5,
		x2: (w / 2) - 1, y2: h - 1
	}).drawEllipse({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x: w / 2, y: h / 2,
		width: min * 0.15, height: min * 0.15
	}).drawEllipse({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x: w / 2, y: h / 2,
		width: min * 0.3, height: min * 0.3
	}).drawEllipse({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x: w / 2, y: h / 2,
		width: min * 0.45, height: min * 0.45
	}).drawEllipse({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x: w / 2, y: h / 2,
		width: min * 0.6, height: min * 0.6
	}).drawEllipse({
		layer: true,
		strokeStyle: '#CCC',
		strokeWidth: 2,
		x: w / 2, y: h / 2,
		width: min * 0.75, height: min * 0.75
	}).drawText({
		layer: true,
		fillStyle: '#CCC',
		x: (w / 2) - ((min * 0.17) / 2), y: (h / 2) + ((min * 0.17) / 2),
		fontSize: 10,
		fontFamily: 'Arial',
		text: '40%'
	}).drawText({
		layer: true,
		fillStyle: '#CCC',
		x: (w / 2) - ((min * 0.28) / 2), y: (h / 2) + ((min * 0.28) / 2),
		fontSize: 10,
		fontFamily: 'Arial',
		text: '60%'
	}).drawText({
		layer: true,
		fillStyle: '#CCC',
		x: (w / 2) - ((min * 0.39) / 2), y: (h / 2) + ((min * 0.39) / 2),
		fontSize: 10,
		fontFamily: 'Arial',
		text: '80%'
	}).drawText({
		layer: true,
		fillStyle: '#CCC',
		x: (w / 2) - ((min * 0.50) / 2), y: (h / 2) + ((min * 0.50) / 2),
		fontSize: 10,
		fontFamily: 'Arial',
		text: '100%'
	}).drawText({
		layer: true,
		name: 'tipo2A',
		fillStyle: '#CCC',
		x: (w / 2) + 10, y: 10,
		fontSize: 10,
		fontFamily: 'Arial',
		align: 'left',
		respectAlign: true,
		maxWidth: min * 0.10,
		text: 'esperienza concreta'
	}).drawText({
		layer: true,
		name: 'tipo2B',
		fillStyle: '#CCC',
		x: w - 5, y: (h / 2) + 20,
		fontSize: 10,
		fontFamily: 'Arial',
		align: 'right',
		respectAlign: true,
		maxWidth: min * 0.10,
		text: 'osservazione riflessiva'
	}).drawText({
		layer: true,
		name: 'tipo2C',
		fillStyle: '#CCC',
		x: (w / 2) - 10, y: h - 10,
		fontSize: 10,
		fontFamily: 'Arial',
		align: 'right',
		respectAlign: true,
		maxWidth: min * 0.10,
		text: 'concettualizzazione astratta'
	}).drawText({
		layer: true,
		fillStyle: '#CCC',
		name: 'tipo2D',
		x: 5, y: (h / 2) - 20,
		fontSize: 10,
		fontFamily: 'Arial',
		align: 'left',
		respectAlign: true,
		maxWidth: min * 0.10,
		text: 'sperimentazione attiva'
	}).drawText({
		layer: true,
		name: 'tipo12',
		fillStyle: '#CCC',
		x: w - 30, y: 30,
		fontSize: 20,
		fontFamily: 'Arial',
		align: 'right',
		respectAlign: true,
		text: 'DIVERGENTE'
	}).drawText({
		layer: true,
		name: 'tipo14',
		fillStyle: '#CCC',
		x: w - 30, y: h - 30,
		fontSize: 20,
		fontFamily: 'Arial',
		align: 'right',
		respectAlign: true,
		text: 'ASSIMILATIVO'
	}).drawText({
		layer: true,
		name: 'tipo13',
		fillStyle: '#CCC',
		x: 30, y: h - 30,
		fontSize: 20,
		fontFamily: 'Arial',
		align: 'left',
		respectAlign: true,
		text: 'CONVERGENTE'
	}).drawText({
		layer: true,
		name: 'tipo11',
		fillStyle: '#CCC',
		x: 30, y: 30,
		fontSize: 20,
		fontFamily: 'Arial',
		align: 'left',
		respectAlign: true,
		text: 'ADATTIVO'
	});

	var kec = parseInt($('#kEC').val());
	var kor = parseInt($('#kOR').val());
	var kca = parseInt($('#kCA').val());
	var ksa = parseInt($('#kSA').val());
	var tipo1 = $('#tipo1').val();
	var tipo2 = $('#tipo2').val();
	
	var coords = new Array();
	
	var vmin = 0.17;
	var vmax = 0.73;
	
	var ec_max = 20;
	var ec_min = 11;
	var ec_step = (vmax - vmin) / (ec_max - ec_min);
	
	var c = { x: w / 2, y: (h / 2) - ((min * 0.075) / 2) };
	var found = false;
	for(var i = ec_min; i <= ec_max; i++) {
		$('#canvas').drawEllipse({
			layer: true,
			fillStyle: '#CCC',
			x: w / 2, y: (h / 2) - (min * (vmin + ((i - ec_min) * ec_step)) / 2),
			width: 7, height: 7
		}).drawText({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) - 15, y: (h / 2) - (min * (vmin + ((i - ec_min) * ec_step)) / 2),
			fontSize: 10,
			fontFamily: 'Arial',
			text: i
		});
		
		if(i == kec) {
			c.y = (h / 2) - (min * (vmin + ((i - ec_min) * ec_step)) / 2);
			found = true;
		}
	}
	if(!found && kec >= i) {
		c.y = (h / 2) - ((min * 0.85) / 2);
	}
	coords.push(c);
	
	var or_max = 19;
	var or_min = 10;
	var or_step = (vmax - vmin) / (or_max - or_min);
	
	var c = { x: (w / 2) + ((min * 0.075) / 2), y: h / 2 };
	var found = false;
	for(var i = or_min; i <= or_max; i++) {
		$('#canvas').drawEllipse({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) + (min * (vmin + ((i - or_min) * or_step)) / 2), y: h / 2,
			width: 7, height: 7
		}).drawText({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) + (min * (vmin + ((i - or_min) * or_step)) / 2), y: (h / 2) - 15,
			fontSize: 10,
			fontFamily: 'Arial',
			text: i
		});
		
		if(i == kor) {
			c.x = (w / 2) + (min * (vmin + ((i - or_min) * or_step)) / 2);
			found = true;
		}
	}
	if(!found && kor >= i) {
		c.x = (w / 2) + ((min * 0.85) / 2);
	}
	coords.push(c);
	
	var ca_max = 23;
	var ca_min = 15;
	var ca_step = (vmax - vmin) / (ca_max - ca_min);
	
	var c = { x: w / 2, y: (h / 2) + ((min * 0.075) / 2) };
	var found = false;
	for(var i = ca_min; i <= ca_max; i++) {
		$('#canvas').drawEllipse({
			layer: true,
			fillStyle: '#CCC',
			x: w / 2, y: (h / 2) + (min * (vmin + ((i - ca_min) * ca_step)) / 2),
			width: 7, height: 7
		}).drawText({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) - 15, y: (h / 2) + (min * (vmin + ((i - ca_min) * ca_step)) / 2),
			fontSize: 10,
			fontFamily: 'Arial',
			text: i
		});
		
		if(i == kca) {
			c.y = (h / 2) + (min * (vmin + ((i - ca_min) * ca_step)) / 2);
			found = true;
		}
	}
	if(!found && kca >= i) {
		c.y = (h / 2) + ((min * 0.85) / 2);
	}
	coords.push(c);
	
	var sa_max = 20;
	var sa_min = 13;
	var sa_step = (vmax - vmin) / (sa_max - sa_min);
	
	var c = { x: (w / 2) - ((min * 0.075) / 2), y: h / 2 };
	var found = false;
	for(var i = sa_min; i <= sa_max; i++) {
		$('#canvas').drawEllipse({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) - (min * (vmin + ((i - sa_min) * sa_step)) / 2), y: h / 2,
			width: 7, height: 7
		}).drawText({
			layer: true,
			fillStyle: '#CCC',
			x: (w / 2) - (min * (vmin + ((i - sa_min) * sa_step)) / 2), y: (h / 2) - 15,
			fontSize: 10,
			fontFamily: 'Arial',
			text: i
		});
		
		if(i == ksa) {
			c.x = (w / 2) - (min * (vmin + ((i - sa_min) * sa_step)) / 2);
			found = true;
		}
	}
	if(!found && ksa >= i) {
		c.x = (w / 2) - ((min * 0.85) / 2);
	}
	coords.push(c);
	
	drawFirst();
	
	function drawFirst() {
		$('#canvas').drawLine({
			layer: true,
			strokeStyle: '#333',
			strokeWidth: 3,
			x1: coords[0].x, y1: coords[0].y,
			x2: coords[0].x, y2: coords[0].y,
			name: 'first'
		}).animateLayer('first', {
			x1: coords[0].x, y1: coords[0].y,
			x2: coords[1].x, y2: coords[1].y,
		}, 1500, function() {
			drawSecond();
		});
	}
	
	function drawSecond() {
		$('#canvas').drawLine({
			layer: true,
			strokeStyle: '#333',
			strokeWidth: 3,
			x1: coords[1].x, y1: coords[1].y,
			x2: coords[1].x, y2: coords[1].y,
			name: 'second'
		}).animateLayer('second', {
			x1: coords[1].x, y1: coords[1].y,
			x2: coords[2].x, y2: coords[2].y,
		}, 1500, function() {
			drawThird();
		});
	}
	
	function drawThird() {
		$('#canvas').drawLine({
			layer: true,
			strokeStyle: '#333',
			strokeWidth: 3,
			x1: coords[2].x, y1: coords[2].y,
			x2: coords[2].x, y2: coords[2].y,
			name: 'third'
		}).animateLayer('third', {
			x1: coords[2].x, y1: coords[2].y,
			x2: coords[3].x, y2: coords[3].y,
		}, 1500, function() {
			drawFourth();
		});
	}
	
	function drawFourth() {
		$('#canvas').drawLine({
			layer: true,
			strokeStyle: '#333',
			strokeWidth: 3,
			x1: coords[3].x, y1: coords[3].y,
			x2: coords[3].x, y2: coords[3].y,
			name: 'fourth'
		}).animateLayer('fourth', {
			x1: coords[3].x, y1: coords[3].y,
			x2: coords[0].x, y2: coords[0].y,
		}, 1500, function() {
			finalResults();
		});
	}
	
	function finalResults() {
		$('#canvas').animateLayer('tipo1' + tipo1, {
			fillStyle: '#000'
		}, 1500).animateLayer('tipo2' + tipo2, {
			fillStyle: '#000'
		}, 1500, function() {
			$('div#risultati').css({opacity: 0.0, visibility: "visible"}).animate({opacity: 1.0}, 1000);
		});
	}
	
}

function checkForm() {
	for(var i = 0; i < $('select.answer').size(); i+=4) {
		var a1 = $('select.answer').eq(i).val();
		var a2 = $('select.answer').eq(i + 1).val();
		var a3 = $('select.answer').eq(i + 2).val();
		var a4 = $('select.answer').eq(i + 3).val();
		var tot = parseInt(a1) + parseInt(a2) + parseInt(a3) + parseInt(a4);
		
		if(tot != 10) {
			alert("ATTENZIONE! Sulla riga " + ((i / 4) + 1) + " non hai indicato valori tutti diversi");
			return false;
		}
	}
	var email = $.trim($('input[name="email"]').val());
	var re = /\S+@\S+\.\S+/;
	if(!re.test(email)) {
		alert("ATTENZIONE! Inserisci un indirizzo email valido");
		return false;
	}
	return true;
}