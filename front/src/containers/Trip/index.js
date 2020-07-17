import { connect } from 'react-redux';

import Trip from 'src/components/Trip';

import {
  updateSuggestionField,
  addSuggestion,
  fetchTrip,
  saveTrip,
} from 'src/actions/trip';

const mapStateToProps = (state) => ({
  trip: state.trip.trip,
  suggestionDescription: state.trip.suggestionDescription,
  suggestionTitle: state.trip.suggestionTitle,
  isLoading: state.trip.isLoading,
});

const mapDispatchToProps = (dispatch) => ({
  changeField: (newValue, name) => {
    dispatch(updateSuggestionField(newValue, name));
  },
  handleSuggestion: () => {
    console.log('container handleSugg');
    dispatch(addSuggestion());
  },
  fetchTrip: (tripId) => {
    dispatch(fetchTrip(tripId));
  },
  saveTrip: () => {
    dispatch(saveTrip());
  },
});

export default connect(mapStateToProps, mapDispatchToProps)(Trip);
