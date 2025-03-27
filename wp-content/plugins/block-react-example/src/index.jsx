import './index.scss'
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon} from '@wordpress/components';

const attributes = {
    question: {
        type: 'string',
    },
    answer: {
        type: 'array',
        default: [""]
    }

};

function EditComponent() {
    return function (props) {

        function updateQuestion(value) {
            props.setAttributes({question: value})
        }
        return (
            <div className="block-react-example">
                <TextControl label="Questions ??" value={props.attributes.question} onChange={updateQuestion} style={{fontSize: '20px'}}/>
                <p style={{fontSize: '13px', margin: "20px 0 8px 0"}}>Answer:</p>
                <Flex>
                    <FlexBlock>
                        <TextControl></TextControl>
                    </FlexBlock>
                    <FlexItem>
                        <Button>
                            <Icon className="mark-as-correct" icon="star-empty"/>
                        </Button>
                    </FlexItem>
                    <FlexItem>
                        <Button isLink className="attention-delete">
                            Delete
                        </Button>
                    </FlexItem>
                </Flex>
                <Button isPrimary> Add another answer </Button>

            </div>
        )
    };
}

wp.blocks.registerBlockType('block-example/block-react-example', {
    title: 'Block React Example',
    icon: 'smiley',
    category: 'common',
    attributes: attributes,
    edit: EditComponent(),
    save: function () {
        return null
    },

})