import { createRoot } from '@wordpress/element';
import './styles/frontend.scss';
// Get all elements with the class
const divsToUpdate = document.querySelectorAll('.paying-attention-me');

// Loop through each element
divsToUpdate.forEach(div => {
    const root = createRoot(div); // Create a root for each individual div
    root.render(<Quiz />);        // Render the Quiz component in this root
    div.classList.remove('paying-attention-me');
});

function Quiz() {
    return (
        <div className="paying-attention-frontend">
            Hello React with
        </div>
    );
}