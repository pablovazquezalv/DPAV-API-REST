import React from 'react';

const Perros = ({ dato }) => {
    return (
        <div>
            <h1>Componente React cargado con Inertia</h1>
            <p>Valor pasado desde Laravel: {dato}</p>
        </div>
    );
};

export default Perros;
