var $j = jQuery.noConflict(); 

$j(document).ready(function(){
    $j.easing.backout = function(x, t, b, c, d){
			var s=1.70158;
			return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
		};
		
		$j('#screen').scrollShow({
			elements:'img',//elements selector (relative to view)
			itemSize:{width:200},
			view:'#view',
			content:'#images',
			easing:'backout',
			wrappers:'link,crop',
			navigators:'a[id]',
			navigationMode:'sr',
			circular:true,
			start:0
		});

});