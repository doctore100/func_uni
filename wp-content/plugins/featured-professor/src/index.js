import "./index.scss"
import {useSelect} from "@wordpress/data"
import {useEffect, useState} from "react"
import apiFetch from "@wordpress/api-fetch"

const __ = wp.i18n.__

wp.blocks.registerBlockType("ourplugin/featured-professor", {
    title: "Professor Callout",
    description: "Include a short description and link to a professor of your choice",
    icon: "welcome-learn-more",
    category: "common",
    attributes: {
        profId: {
            type: "string",
        }
    },
    edit: EditComponent,
    save: function () {
        return null
    }
})

function EditComponent(props) {
    const [thePreview, setThePreview] = useState("")

    useEffect(() => {
        if (props.attributes.profId) {
            updateTheMeta()

            async function fetchData() {
                const response = await apiFetch({
                    path: `/featuresProfessor/v1/get_html?profId=${props.attributes.profId}`,
                    method: "GET",
                })
                setThePreview(response)
            }

            fetchData()
        }
    }, [props.attributes.profId])


    function updateTheMeta() {
        const profForMeta = wp.data.select("core/block-editor")
            .getBlocks()
            .filter(block => block.name === "ourplugin/featured-professor")
            .map(block => block.attributes.profId)
            .filter((x, index, arr) => {
                return arr.indexOf(x) === index;
            })

        wp.data.dispatch("core/editor").editPost({
            meta: {
                featuredProfessor: profForMeta
            }
        })
    }

    const allProfessors = useSelect(select => {
        return select("core").getEntityRecords("postType", "professor", {perPage: -1})
    });
    console.log(allProfessors)
    if (allProfessors === undefined || allProfessors === null) return <div>Loading...</div>

    return (
        <div className="featured-professor-wrapper">
            <div className="professor-select-container">
                <select onChange={(e) => props.setAttributes({profId: e.target.value})} value={props.attributes.profId}>
                    <option value="">{__("Select a Professor", "featured-professor")}</option>
                    {allProfessors.map(prof => {
                        return (<option key={prof.id} value={prof.id}>
                            {prof.title.rendered}
                        </option>)
                    })}
                </select>
            </div>
            <div dangerouslySetInnerHTML={{__html: thePreview}}>
            </div>
        </div>
    )
}