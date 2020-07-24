import { connect } from 'react-redux';

import ActivityForm from 'src/components/ActivityForm';

import { updateActivityField, addActivity } from 'src/actions/trip';

const mapStateToProps = (state) => ({
  activityTitle: state.trip.activityTitle,
  activityDescritption: state.trip.activityDescription,
  activityStartDate: state.trip.activityStartDate,
  activityEndDate: state.trip.activityEndDate,
  activityCategory: state.trip.activityCategory,

});

const mapDispatchToProps = (dispatch) => ({
  changeField: (newValue, name) => {
    dispatch(updateActivityField(newValue, name));
  },

  handleAddActivity: () => {
    dispatch(addActivity());
  },
});

export default connect(mapStateToProps, mapDispatchToProps)(ActivityForm);