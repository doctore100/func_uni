wp.blocks.registerBlockType('block-example/block-react-example', {
    title: 'Block React Example',
    icon: 'smiley',
    category: 'common',
    edit: function () {
        return wp.element.createElement(
            'h3',
            null,
            'Hello World this is a react block bot in the edit function'
        );
    },
    save: function () {
        return wp.element.createElement(
            'h2',
            null,
            'Hello World this is a react bot the save function'
        )
    },
})