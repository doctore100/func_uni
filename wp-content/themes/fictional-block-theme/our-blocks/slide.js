import apiFetch from '@wordpress/api-fetch';
import {Button, PanelBody, PanelRow} from '@wordpress/components';
import {InnerBlocks, InspectorControls, MediaUpload, MediaUploadCheck} from '@wordpress/block-editor';
import {registerBlockType} from "@wordpress/blocks"
import {useEffect} from '@wordpress/element'

registerBlockType('ourblocktheme/slide', {
    title: 'Slide',
    supports: {
        align: ["full"]
    },
    attributes: {
        align: {type: "string", default: "full"},
        imgID: {type: "number"},
        imgURL: {type: "string", default: banner.fallback_image}
    },

    save: SaveComponent,
    edit: EditComponent,
})

function EditComponent(props) {
    const useMeLater = (
        <>
            <h1 className="headline headline--large">
                Welcome!
            </h1>
            <h2 className="headline headline--medium">
                We think you&rsquo;ll like it here.
            </h2>
            <h3 className="headline headline--small">
                Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?
            </h3>
            <a href="#" className="btn btn--large btn--blue">
                Find Your Major
            </a>
        </>
    )
    useEffect(() => {
        if (props.attributes.imgID) {
            async function fetchImage() {
                const response = await apiFetch({
                    path: `/wp/v2/media/${props.attributes.imgID}`,
                    method: "GET",
                })
                props.setAttributes({imgURL: response.media_details.sizes.pageBanner.source_url})
            }

            fetchImage()

        }
    }, [props.attributes.imgID])

    function onFileSelect(file) {
        console.log(file)
        props.setAttributes({imgID: file.id})
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title="Background Image" initialOpen={true}>
                    <PanelRow>
                        <MediaUploadCheck>
                            <MediaUpload onSelect={onFileSelect}
                                         value={props.attributes.imgID}
                                         render={({open}) => {
                                             return <Button onClick={open}>Upload Image</Button>
                                         }}/>
                        </MediaUploadCheck>
                    </PanelRow>
                </PanelBody>
            </InspectorControls>

            <div className="hero-slider__slide" style={{backgroundImage: `url('${props.attributes.imgURL}')`}}>
                <div className="hero-slider__interior container">
                    <div className="hero-slider__overlay t-center">
                        <InnerBlocks
                            allowedBlocks={["ourblocktheme/generic-button", "ourblocktheme/generic-heading"]}/>

                    </div>
                </div>
            </div>
        </>
    )
}

function SaveComponent() {
    return <InnerBlocks.Content/>
}