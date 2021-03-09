/* eslint-disable jsx-a11y/label-has-associated-control */
// == Import : npm
import React from 'react';
import PropTypes from 'prop-types';

import './fieldSelect.scss';

// == Import : local

// == Composant
const FieldSelect = ({
  value,
  name,
  onChange,
  labelTitle,
}) => {
  const handleChange = (evt) => {
    onChange(evt.target.value, name);
  };

  const inputId = `field-${name}`;

  return (
    <div className="field">
      <label
        htmlFor={inputId}
        className="field-label"
      >{labelTitle}
        <select value={value} onChange={handleChange}>
          <option defaultValue=""> Choisir une catégorie</option>
          <option value="1">restaurant/bar</option>
          <option value="2">sortie culturelle</option>
          <option value="3">visite guidée</option>""
          <option value="4">espace vert/parc</option>
          <option value="5">plage/piscine/lac</option>
          <option value="6">concert/spectacle</option>""
          <option value="7">shopping</option>
          <option value="8">attraction touristique (village..)</option>
          <option value="9">randonnée</option>""
          <option value="10">sport (canoé, foot, ..)</option>
          <option value="11">sortie nocturne</option>
          <option value="12">parc d'attraction</option>""
          <option value="13">Autres...</option>
        </select>
      </label>
    </div>
  );
};

FieldSelect.propTypes = {
  value: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  onChange: PropTypes.func.isRequired,
  labelTitle: PropTypes.string.isRequired,
};

// Valeurs par défaut pour les pro
// == Export
export default FieldSelect;
