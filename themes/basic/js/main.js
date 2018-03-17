function show_box(url) {
	box=window.open(url,'name','height=500,width=700,scrollbars=yes');
	if (window.focus) {box.focus()}
	return false;
}