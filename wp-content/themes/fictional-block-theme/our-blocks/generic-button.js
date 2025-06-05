import ourColors from "../inc/ourColors"
import { registerBlockType } from "@wordpress/blocks"
import {
    __experimentalLinkControl as LinkControl,
    getColorObjectByColorValue,
    BlockControls,
    RichText,
    InspectorControls
} from "@wordpress/block-editor"
import {
    Button,
    Popover,
    ToolbarButton,
    ToolbarGroup,
    PanelBody,
    PanelRow,
    ColorPalette
} from "@wordpress/components"
import { link } from "@wordpress/icons"
import { useState } from "@wordpress/element"

registerBlockType("ourblocktheme/generic-button", {
    title: "Generic Button",
    attributes: {
        text: { type: "string" },
        size: { type: "string", default: "large" },
        linkObject: {
            type: "object",
            default: { url: "" }
        },
        colorName: { type: "string", default: "blue" }
    },
    edit: EditComponent,
    save: SaveComponent
})

function EditComponent(props) {
    const [isLinkPickerVisible, setIsLinkPickerVisible] = useState(false)

    // Safely get the current color value
    const foundColor = ourColors.find(color => color.name === props.attributes.colorName)
    const currentColorValue = foundColor ? foundColor.color : ""

    function handleColorChange(colorCode) {
        const { name } = getColorObjectByColorValue(ourColors, colorCode)
        props.setAttributes({ colorName: name })
    }

    return (
        <>
            <BlockControls>
                <ToolbarGroup>
                    <ToolbarButton
                        icon={link}
                        onClick={() => setIsLinkPickerVisible(prev => !prev)}
                    />
                </ToolbarGroup>
                <ToolbarGroup>
                    {["large", "medium", "small"].map(size => (
                        <ToolbarButton
                            key={size}
                            isPressed={props.attributes.size === size}
                            onClick={() => props.setAttributes({ size })}
                        >
                            {size.charAt(0).toUpperCase() + size.slice(1)}
                        </ToolbarButton>
                    ))}
                </ToolbarGroup>
            </BlockControls>

            <InspectorControls>
                <PanelBody title="Background Color" initialOpen={true}>
                    <PanelRow>
                        <ColorPalette
                            disableCustomColors={true}
                            clearable={false}
                            colors={ourColors}
                            value={currentColorValue}
                            onChange={handleColorChange}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>

            <RichText
                allowedFormats={[]}
                tagName="a"
                className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`}
                value={props.attributes.text}
                onChange={text => props.setAttributes({ text })}
            />

            {isLinkPickerVisible && (
                <Popover
                    position="middle center"
                    onFocusOutside={() => setIsLinkPickerVisible(false)}
                >
                    <LinkControl
                        settings={[]}
                        value={props.attributes.linkObject}
                        onChange={value => props.setAttributes({ linkObject: value })}
                    />
                    <Button
                        variant="primary"
                        onClick={() => setIsLinkPickerVisible(false)}
                        style={{ display: "block", width: "100%" }}
                    >
                        Confirm Link
                    </Button>
                </Popover>
            )}
        </>
    )
}

function SaveComponent(props) {
    return (
        <a
            href={props.attributes.linkObject.url}
            className={`btn btn--${props.attributes.size} btn--${props.attributes.colorName}`}
        >
            {props.attributes.text}
        </a>
    )
}
