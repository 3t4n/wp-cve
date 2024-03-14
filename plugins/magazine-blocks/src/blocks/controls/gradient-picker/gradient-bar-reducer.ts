import type {
	CustomGradientBarIdleState,
	CustomGradientBarReducerAction,
	CustomGradientBarReducerState,
} from "./types";

export const gradientBarReducer = (
	state: CustomGradientBarReducerState,
	action: CustomGradientBarReducerAction
): CustomGradientBarReducerState => {
	switch (action.type) {
		case "MOVE_INSERTER":
			if (state.id === "IDLE" || state.id === "MOVING_INSERTER") {
				return {
					id: "MOVING_INSERTER",
					insertPosition: action.insertPosition,
				};
			}
			break;
		case "STOP_INSERTER_MOVE":
			if (state.id === "MOVING_INSERTER") {
				return {
					id: "IDLE",
				};
			}
			break;
		case "OPEN_INSERTER":
			if (state.id === "MOVING_INSERTER") {
				return {
					id: "INSERTING_CONTROL_POINT",
					insertPosition: state.insertPosition,
				};
			}
			break;
		case "CLOSE_INSERTER":
			if (state.id === "INSERTING_CONTROL_POINT") {
				return {
					id: "IDLE",
				};
			}
			break;
		case "START_CONTROL_CHANGE":
			if (state.id === "IDLE") {
				return {
					id: "MOVING_CONTROL_POINT",
				};
			}
			break;
		case "STOP_CONTROL_CHANGE":
			if (state.id === "MOVING_CONTROL_POINT") {
				return {
					id: "IDLE",
				};
			}
			break;
	}
	return state;
};
export const gradientBarReducerInitialState: CustomGradientBarIdleState = {
	id: "IDLE",
};
