import "./index.scss"
import {useSelect} from "@wordpress/data"
import {useState, useEffect } from "react"
import apiFetch from "@wordpress/api-fetch"

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
        async function fetchData() {
            const response = await apiFetch({
                path: `/featuresProfessor/v1/get_html?profId=${props.attributes.profId}`,
                method: "GET",
            })
            setThePreview(response)
        }
        fetchData()
    }, [props.attributes.profId])

    const allProfessors = useSelect(select => {
        return select("core").getEntityRecords("postType", "professor", {perPage: -1})
    });
    console.log(allProfessors)
    if (allProfessors === undefined || allProfessors === null) return <div>Loading...</div>

    return (
        <div className="featured-professor-wrapper">
            <div className="professor-select-container">
                <select onChange={(e) => props.setAttributes({profId: e.target.value})} value={props.attributes.profId}>
                    <option value="">Select a Professor</option>
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