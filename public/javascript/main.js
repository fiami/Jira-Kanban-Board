var kanbanboard = {
	init : function(){
		this.dragAndDrop();
	},
	dragAndDrop : function(){
		$( ".draggable" ).draggable({
			snap: '.droppable',
			cursor: 'move',
			revert: true
		});
		$('.droppable').droppable( {
			drop: function(event, ui){
				var source = $(ui.draggable).closest('.droppable');
				// if dragged to a different column
				if(event.target != source){
					kanbanboard.handleDropEvent(event, ui, source)
				}
			},
			hoverClass: 'hovered'
		});
	},
	handleDropEvent : function(event, ui, source) {
		var switchCol = 'switchCol' + ($(source).index()+1) + 'To' + ($(event.target).index()+1);
		var config = window.kanbanSources[window.kanbanCurrentBoard];
		// check if callback exists for columns
		if (typeof config !=  "undefined" && typeof config.callbacks[switchCol] !=  "undefined"){
			// drop issue html into column
			$(ui.draggable).appendTo(event.target);
			var updateFields = config.callbacks[switchCol];
			// check if custom callback function exists
			if(typeof updateFields.custom != "undefined"){
				custom = updateFields.custom;
				// call custom callback function
				config.custom[custom](event, ui, source, updateFields);
			}else{
				// pass fields from callbacks to update via ajax
				kanbanboard.handleAjax(updateFields, $(ui.draggable).data("key"));
			}
		}
	},
	handleAjax : function(updateFields, key){
		var u = JSON.stringify(updateFields);
		var k = JSON.stringify(key);
		$.ajax({
			type:"POST",
			url: kanbanBaseUrl + "/issues/editsample/" + window.kanbanCurrentBoard,
			data:{"fields" : u, "key" : k},
			dataType: "json"
		});
	}
};
$(kanbanboard.init());