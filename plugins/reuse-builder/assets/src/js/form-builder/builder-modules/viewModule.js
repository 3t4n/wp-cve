const init = {
  viewMode: 'preview',
};

export default function viewReducer(state = init, action) {
  switch (action.type) {
    case 'CHANGE_VIEWMODE':
        return {
          ...state,
          viewMode: action.viewMode,
        };
      break;
    default:
      return state;
  }
}


export function changeView(viewMode) {
  return {
    type: 'CHANGE_VIEWMODE',
    viewMode,
  };
}
