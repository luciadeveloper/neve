import PropTypes from 'prop-types';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;

const {
	Dropdown,
	IconButton,
	RangeControl
} = wp.components;

const { Component } = wp.element;

class SizingControl extends Component {
	constructor(props) {
		super( props );
	}

	render() {
		return (
				<div className="neve-responsive-sizing">
					{this.props.options.map( (i, n) => {
						return (
								<div className="nv-sizing-item">
									<Dropdown
											position="top center"
											focusOnMount={false}
											renderToggle={({ isOpen, onToggle }) => (
													<input
															type="number"
															value={i.value}
															min={this.props.min || 0}
															max={this.props.max || 300}
															onFocus={onToggle}
															onFocusOut={onToggle}
															onChange={e => this.props.onChange( i.type,
																	parseInt( e.target.value ) )}
													/>
											)}
											renderContent={({ onToggle }) => (
													<div className="range-control">
														<RangeControl
																value={i.value}
																initialPosition={i.value}
																beforeIcon="minus"
																afterIcon="plus"
																min={this.props.min || 0}
																max={this.props.max}
																onChange={e => this.props.onChange( i.type, e )}
														/>
													</div>
											)}
									/>
									{i.label && (
											<label className="label">
												{i.label}
											</label>
									)}
								</div>
						);
					} )}

					<div className="nv-sizing-link">
						<IconButton
								className={this.props.linked && 'is-linked'}
								icon={ this.props.linked ?
										'admin-links' :
										'editor-unlink'}
								tooltip={ this.props.linked ?
										__( 'Unlink Values' ) :
										__( 'Link Values' )}
								onClick={() => this.props.onLinked()}
						/>
					</div>
				</div>
		);
	}
}

SizingControl.propTypes = {
	onLinked: PropTypes.func.isRequired,
	linked: PropTypes.bool.isRequired
};

export default SizingControl;
