const { InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl, ToggleControl } = wp.components;

const Controls = ( { attributes, setAttributes } ) => (
	<InspectorControls>
		<PanelBody title={ 'Speed' }>
			<RangeControl
				label="Typing speed"
				value={ attributes.typeSpeed }
				onChange={ ( typeSpeed ) => setAttributes( { typeSpeed } ) }
				min={ 1 }
				max={ 1000 }
			/>
			<RangeControl
				label="Start delay"
				value={ attributes.startDelay }
				onChange={ ( startDelay ) => setAttributes( { startDelay } ) }
				min={ 0 }
				max={ 3000 }
			/>
			<RangeControl
				label="Back speed"
				value={ attributes.backSpeed }
				onChange={ ( backSpeed ) => setAttributes( { backSpeed } ) }
				min={ 1 }
				max={ 1000 }
			/>
			<RangeControl
				label="Back delay"
				value={ attributes.backDelay }
				onChange={ ( backDelay ) => setAttributes( { backDelay } ) }
				min={ 0 }
				max={ 3000 }
			/>
		</PanelBody>
		<PanelBody title={ 'Loop' }>
			<ToggleControl
				label="Loop"
				help="Repeats the given sentences"
				checked={ attributes.loop }
				onChange={ ( loop ) => setAttributes( { loop } ) }
			/>
			<RangeControl
				label="Loop count"
				help="Number of loops of the animation (0 is infinite)"
				value={ attributes.loopCount }
				onChange={ ( loopCount ) => setAttributes( { loopCount } ) }
				min={ 0 }
				max={ 100 }
			/>
		</PanelBody>
		<PanelBody title={ 'Misc' }>
			<ToggleControl
				label="Smart backspace"
				help="Does not retype the whole word but starts at a common word in the last sentence"
				checked={ attributes.smartBackspace }
				onChange={ ( smartBackspace ) => setAttributes( { smartBackspace } ) }
			/>
			<ToggleControl
				label="Shuffle sentences"
				help="Randomizes the play order of the given sentences"
				checked={ attributes.shuffle }
				onChange={ ( shuffle ) => setAttributes( { shuffle } ) }
			/>
			<ToggleControl
				label="Fade out"
				help="Word does not type backwards"
				checked={ attributes.fadeOut }
				onChange={ ( fadeOut ) => setAttributes( { fadeOut } ) }
			/>
			<ToggleControl
				label="Show cursor"
				help="Shows the typing cursor"
				checked={ attributes.showCursor }
				onChange={ ( showCursor ) => setAttributes( { showCursor } ) }
			/>
		</PanelBody>
	</InspectorControls>
);

export default Controls;
