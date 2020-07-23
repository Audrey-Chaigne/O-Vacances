import { connect } from 'react-redux';

import TripEdit from 'src/components/TripEdit';

import { addImagePreview } from 'src/actions/settings';

const mapStateToProps = (state) => ({
  file: state.settings.file,

});

const mapDispatchToProps = (dispatch) => ({
  addImagePreview: (url) => {
    dispatch(addImagePreview(url));
  },
});

export default connect(mapStateToProps, mapDispatchToProps)(TripEdit);