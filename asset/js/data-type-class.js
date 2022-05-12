/**
 * Prepare the markup for the DataTypeClass (Item and media of Specific class) data types.
 * The initial population is not done by the resource-form.js attached o:prepare-value listener 
 */
$(document).on('o:prepare-value', function(e, dataType, value, valueObj) {
    var resource_class_tpair = dataType.split('#');
   
    if (resource_class_tpair.length < 2 || ! valueObj){
	// no hash so no class, or nothing to populate with, so all should be done (already) in standard listener
	    return;
    }

    // Prepare the markup for the resource data types with class
    var resourceDataTypes = [
        'resource:item',
        'resource:itemset',
        'resource:media',
    ];
    if (-1 == resourceDataTypes.indexOf(resource_class_tpair[0])) {
	    // unsupported resource type
	    return;
    }

    value.find('span.default').hide();
    var resource = value.find('.selected-resource');
    if (typeof valueObj['display_title'] === 'undefined') {
        valueObj['display_title'] = Omeka.jsTranslate('[Untitled]');
    }
    resource.find('.o-title')
        .removeClass() // remove all classes
        .addClass('o-title ' + valueObj['value_resource_name'])
        .html($('<a>', {href: valueObj['url'], text: valueObj['display_title']}));
    if (typeof valueObj['thumbnail_url'] !== 'undefined') {
        resource.find('.o-title')
            .prepend($('<img>', {src: valueObj['thumbnail_url']}));
    }
});
