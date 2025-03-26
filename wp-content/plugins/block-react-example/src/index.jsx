wp.blocks.registerBlockType('block-example/block-react-example', {
    title: 'Block React Example',
    icon: 'smiley',
    category: 'common',
    attributes: {
        skyColor: {type: 'string', source: 'text', selector: ".skyColor" },
        grassColor: {type: 'string', source: 'text', selector: ".grassColor"}
    },
    edit: function (props) {
        function updateSkyColor(e) {
            props.setAttributes({skyColor: e.target.value})
        }

        function updateGrassColor(e) {
            props.setAttributes({grassColor: e.target.value})
        }

        return (
            <div>
                <input type="text" placeholder="Color of the sky" value={props.attributes.skyColor} onChange={updateSkyColor}></input>
                <input type="text" placeholder="Color of the grass" value={props.attributes.grassColor} onChange={updateGrassColor}></input>
            </div>
        )
    },
    save: function (props) {
        return (
            <p>
                The color of the sky is <span className="skyColor">{props.attributes.skyColor}</span> and the color of the grass
                is <span className="grassColor">{props.attributes.grassColor}</span>
            </p>
        )
    },
})