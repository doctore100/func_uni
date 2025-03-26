const attributes = {
    skyColor: {
        type: 'string',
    },
    grassColor: {
        type: 'string',
    }
};
wp.blocks.registerBlockType('block-example/block-react-example', {
    title: 'Block React Example',
    icon: 'smiley',
    category: 'common',
    attributes: attributes,
    edit: function (props) {
        function updateSkyColor(e) {
            props.setAttributes({skyColor: e.target.value})
        }

        function updateGrassColor(e) {
            props.setAttributes({grassColor: e.target.value})
        }

        return (
            <div>
                <input type="text" placeholder="Color of the sky" value={props.attributes.skyColor}
                       onChange={updateSkyColor}></input>
                <input type="text" placeholder="Color of the grass" value={props.attributes.grassColor}
                       onChange={updateGrassColor}></input>
            </div>
        )
    },
    save: function () {
        return null
    },

})