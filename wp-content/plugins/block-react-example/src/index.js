import './styles/index.scss'
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon} from '@wordpress/components';


const attributes = {
    question: {
        type: "string",
    },
    answer: {
        type: "array",
        default: [""]
    },
    correctAnswer: {
        type: "number",
        default: undefined
    }

};

/**
 * Monitors blocks that are missing answers and locks/unlocks post saving accordingly.
 * Prevents saving posts that contain blocks without answers defined.
 */
(function monitorBlocksMissingAnswers() {
    const {subscribe, select, dispatch} = wp.data;
    const TARGET_BLOCK_NAME = 'block-example/block-react-example';

    const LOCK_ID = 'noanswers';

    let isCurrentlyLocked = false;
    subscribe(() => {
        const blocksMissingAnswers = getBlocksMissingAnswers();

        const shouldBeLocked = blocksMissingAnswers.length > 0;
        // Only update the lock state when it changes
        if (shouldBeLocked !== isCurrentlyLocked) {

            isCurrentlyLocked = shouldBeLocked;
            if (shouldBeLocked) {
                dispatch("core/editor").lockPostSaving(LOCK_ID);
            } else {
                dispatch("core/editor").unlockPostSaving(LOCK_ID);
            }
        }

    });

    /**
     * Returns blocks that match the target name and have undefined answers.
     * @return {Array} Array of blocks missing answers
     */
    function getBlocksMissingAnswers() {
        return select("core/block-editor")
            .getBlocks()
            .filter(block =>
                block.name === TARGET_BLOCK_NAME &&
                block.attributes.correctAnswer === undefined
            );
    }

})()


function EditComponent() {
    return function (props) {
        function updateQuestion(value) {
            props.setAttributes({question: value})
        }

        function deleteAnswer(index) {
            const updates = {};

            // Si el elemento a eliminar es la respuesta correcta, reiniciar correctAnswer
            if (props.attributes.correctAnswer === index) {
                updates.correctAnswer = undefined;
            }

            // Filtrar el array de respuestas para eliminar el elemento en el índice dado
            updates.answer = props.attributes.answer.filter((_, i) => i !== index);

            // Actualizar los atributos con todos los cambios en una sola llamada
            props.setAttributes(updates);
        }

        function markAsCorrect(index) {
            props.setAttributes({correctAnswer: index})
        }

        return (
            <div className="block-react-example">
                <TextControl autoFocus={props.attributes.answer !== undefined} label="Questions ??"
                             value={props.attributes.question} onChange={updateQuestion}
                             style={{fontSize: '20px'}}/>
                <p style={{fontSize: '13px', margin: "20px 0 8px 0"}}>Answer:</p>
                {props.attributes.answer.map((item, index) => {
                    return (
                        <Flex>
                            <FlexBlock>
                                <TextControl value={item} onChange={(newValue) => {
                                    const newAnswer = [...props.attributes.answer]
                                    newAnswer[index] = newValue
                                    props.setAttributes({answer: newAnswer})
                                }}/>
                            </FlexBlock>
                            <FlexItem>
                                <Button onClick={() => markAsCorrect(index)}>
                                    <Icon className="mark-as-correct"
                                          icon={props.attributes.correctAnswer === index ? "star-filled" : "star-empty"}/>
                                </Button>
                            </FlexItem>
                            <FlexItem>
                                <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>
                                    Delete
                                </Button>
                            </FlexItem>
                        </Flex>
                    )
                })}
                <Button isPrimary onClick={() => {
                    const newAnswer = [...props.attributes.answer]
                    newAnswer.push(undefined)
                    props.setAttributes({answer: newAnswer})
                }}> Add another answer </Button>

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
