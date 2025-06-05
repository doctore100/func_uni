wp.blocks.registerBlockType('ourblocktheme/events-and-blocks', {
    title: 'Events and Blocks',
    edit: function () {
        return wp.element.createElement(
            "div",
            {
                className: "our-placeholder-block"
            },
            "Events and Blocks placeholder",
        )
    },
    save: function () {
        return null
    },
})