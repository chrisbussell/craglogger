// Test and load polyfills as appropriate
Modernizr.load([
	{
		test: Modernizr.boxsizing,
		nope: '/assets/js/base/polyfills/borderBoxModel-min.js'
	},
	{
		test: Modernizr.input.placeholder,
		nope: '/assets/js/base/polyfills/placeholders.min.js'
	}
]);

//initialise slicknav
$(function(){
	$('header nav ul').slicknav({
		allowParentLinks: true
	});
});