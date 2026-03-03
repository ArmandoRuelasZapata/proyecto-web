describe('Pruebas de Registro de Nuevo Administrador - MoviDGO', () => {
  
  beforeEach(() => {
    cy.visit('http://localhost:81/dev/public/register');
  });

  it('CP-003: Crear nuevo administrador con campos vacíos', () => {
    cy.get('#submit-btn').click();
    cy.get('#error-message')
      .should('be.visible')
      .and('contain', 'Por favor llena todos los campos.');
    cy.get('#submit-btn')
      .should('not.be.disabled')
      .and('contain', 'Registrarse');
  });


  it('CP-004: Crear nuevo administrador con campos completos (Registro Exitoso)', () => {
    cy.on('window:alert', (text) => {
      expect(text).to.equal('¡Registro exitoso!');
    });
    cy.get('#name').type('Admin Prueba');
    cy.get('#email').type('admin_test@gmail.com'); 
    cy.get('#password').type('Ruza020218jk');
    cy.get('#password-confirm').type('Ruza020218jk');
    cy.get('#submit-btn').click();
  });


  it('CP-005: Rellenar contraseña sin los mínimos caracteres', () => {
    cy.get('#name').type('Usuario Debil');
    cy.get('#email').type('debil_@movidgo.com');
    cy.get('#password').type('123');
    cy.get('#password-confirm').type('123');
    cy.get('#submit-btn').click();
    cy.get('#error-message')
      .should('be.visible')
      .and('contain', 'Contraseña muy débil.');
  });


  it('CP-006: Rellenar correo electrónico sin formato válido (@ y .)', () => {
    cy.get('#name').type('Usuario Formato');
    cy.get('#email').type('admingmail.com');
    cy.get('#password').type('admin123');
    cy.get('#password-confirm').type('admin123');

    cy.get('#submit-btn').click();

    cy.get('#error-message')
      .should('be.visible')
      .and('contain', 'Correo inválido.');
  });

  it('Validación Extra: Contraseñas no coinciden', () => {
    cy.get('#name').type('Test Coincidencia');
    cy.get('#email').type('test_@test.com');
    cy.get('#password').type('Password123');
    cy.get('#password-confirm').type('Diferente123');
    cy.get('#submit-btn').click();
    cy.get('#error-message')
      .should('be.visible')
      .and('contain', 'Las contraseñas no coinciden.');
  });

});