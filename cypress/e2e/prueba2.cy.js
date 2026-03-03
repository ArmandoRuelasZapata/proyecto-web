describe('MoviDGO: Login y Soporte', () => {  
    it('Flujo Completo: Login, Crear y Eliminar', () => {
        // 1. Login rápido
        cy.visit('http://localhost:81/dev/public/login');
        cy.get('#email').type('erik@test.com');
        cy.get('#password').type('123456');
        cy.get('#submit-btn').click();

        // 2. Ir a Soporte
        cy.url().should('include', '/home');
        cy.visit('http://localhost:81/dev/public/solicitudes');

        // 3. Crear Guía
        cy.get('#guia-titulo').type('Test Rápido');
        cy.get('#guia-contenido').type('Contenido de prueba.');
        cy.get('#btn-guardar-guia').click();

        // Esperar modal y cerrar
        cy.get('#confirm-overlay', { timeout: 7000 }).should('have.class', 'active');
        cy.get('#modal-btn-ok').click();

        // 4. Eliminar Guía
        cy.get('.btn-delete').first().click();
        
        // Esperar confirmación y aceptar
        cy.get('#confirm-overlay').should('have.class', 'active');
        cy.get('#modal-btn-ok').click();
        
        // Verificación final: el modal desaparece
        cy.get('#confirm-overlay').should('not.have.class', 'active');
    });
});