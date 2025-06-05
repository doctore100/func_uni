import {registerBlockType} from "@wordpress/blocks"
import {RichText, BlockControls} from "@wordpress/block-editor"
import {ToolbarGroup, ToolbarButton} from "@wordpress/components"

registerBlockType('ourblocktheme/generic-heading', {
    title: "Generic Heading",
    attributes: {
        text: {
            type: "string",
        },
        size: {
            type: "string",
            default: "large"
        }
    },
    save: SaveComponent,
    edit: EditComponent
})

function EditComponent(props) {
    return (
        <>
            <BlockControls>
                <ToolbarGroup>
                    {["large", "medium", "small"].map(size => (
                        <ToolbarButton
                            key={size}
                            isPressed={props.attributes.size === size}
                            onClick={() => props.setAttributes({size})}
                        >
                            {size.charAt(0).toUpperCase() + size.slice(1)}
                        </ToolbarButton>
                    ))}
                </ToolbarGroup>
            </BlockControls>
            <RichText allowedFormats={["core/bold"]} tagName="h1"
                      className={`headline headline--${props.attributes.size}`}
                      value={props.attributes.text} onChange={x => props.setAttributes({text: x})}/>
        </>
    )
}

function SaveComponent(props) {
    function crateTagNames() {
        switch (props.attributes.size) {
            case "large":
                return "h1"
            case "medium":
                return "h2"
            case "small":
                return "h3"
            default:
                break;
        }
    }
    return <RichText.Content
        tagName={crateTagNames()}
        className={`headline headline--${props.attributes.size}`}
        value={props.attributes.text}/>
}