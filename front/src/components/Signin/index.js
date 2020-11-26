import React from 'react';
import { Link, Redirect } from 'react-router-dom';
import PropTypes from 'prop-types';
import Button from 'src/components/elements/Button';
import { Helmet } from 'react-helmet';
import Field from './Field';

import './signin.scss';

const Signin = ({
  firstname,
  lastname,
  email,
  password,
  changeField,
  handleSignin,
  error,
  isAuthenticated,
}) => {
  const handleSubmit = (event) => {
    event.preventDefault();
    handleSignin();
  };

  return (
    <main className="signin">
      {isAuthenticated && <Redirect to="/" />}
      <Helmet>
        <title>Inscription</title>
        <meta name="description" content="Inscription" />
      </Helmet>
      <div className="connection-container">

        <h1>Bienvenue voyageur !</h1>
        <form className="signin-form-element" onSubmit={handleSubmit}>
          <Field
            name="firstname"
            placeholder="Prénom"
            onChange={changeField}
            value={firstname}
            required
          />
          <Field
            name="lastname"
            placeholder="Nom"
            onChange={changeField}
            value={lastname}
          />
          <Field
            name="email"
            type="email"
            placeholder="Adresse Email"
            onChange={changeField}
            value={email}
            required
            pattern="^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-z]{2,4}$"
          />
          <Field
            name="password"
            type="password"
            placeholder="Mot de passe"
            note="Il doit contenir entre 8 et 24 caractères, au moins 1 majuscule, 1 minuscule et 1 caractère spécial (#@%*.-)"
            onChange={changeField}
            value={password}
            required
            pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[A-Za-z0-9_#@%\*\.\-]{8,24}$"
          />
          {error && (
          <p className="error-message">{error}</p>
          )}
          <Button
            color="primary"
            type="submit"
            haveClassName="signin-form-button"
          >
            S'inscrire
          </Button>
        </form>
        <div className="helper">
          <hr />
          <p>
            Déjà un compte ? <Link to="/login">Connexion</Link>
          </p>
        </div>
      </div>
    </main>
  );
};

Signin.propTypes = {
  firstname: PropTypes.string.isRequired,
  lastname: PropTypes.string.isRequired,
  email: PropTypes.string.isRequired,
  password: PropTypes.string.isRequired,
  changeField: PropTypes.func.isRequired,
  handleSignin: PropTypes.func.isRequired,
  isAuthenticated: PropTypes.bool.isRequired,
};

export default Signin;
