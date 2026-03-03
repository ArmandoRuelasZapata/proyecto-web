describe('Login Firebase - Proyecto Integrador', () => {

    // URL base centralizada
    const baseUrl = 'http://localhost:81/dev/public'

    beforeEach(() => {
        cy.clearCookies()
        cy.clearLocalStorage()
        // Visitamos el CRUD (que nos redirige al login si no hay sesión)
        cy.visit(`${baseUrl}/crud`)
    })

    it('CP-01 - Renderiza correctamente el formulario', () => {
        cy.get('#email').should('be.visible')
        cy.get('#password').should('be.visible')
        cy.get('#submit-btn').should('contain', 'Iniciar sesión')
    })

    it('CP-04 - Login exitoso y redirección a Home', () => {
        cy.get('#email').type('francisco.hernandez.itid@unipolidgo.edu.mx', { delay: 50 })
        cy.get('#password').type('tr7es9nr', { delay: 50 })
        cy.get('#submit-btn').click()

        // Verificamos que entramos al Home
        cy.url({ timeout: 10000 }).should('include', '/home')
    })

    // --- NUEVA PRUEBA ---
    it('CP-05 - Login exitoso y navegar a Leer Contactos', () => {
        // 1. Proceso de Login
        cy.get('#email').type('francisco.hernandez.itid@unipolidgo.edu.mx')
        cy.get('#password').type('tr7es9nr')
        cy.get('#submit-btn').click()

        // 2. Esperar a que el login sea procesado
        cy.url().should('include', '/home')

        // 3. Navegar manualmente a Leer Contactos
        cy.visit(`${baseUrl}/leer-contactos`)

        // 5. Verificación de que estamos en la página correcta
        cy.url().should('include', '/leer-contactos')

        //4. Navegar a reportes
        cy.visit(`${baseUrl}/reportes`)
        cy.wait(200)
        cy.get('.btn-fav-toggle').first().should('be.visible').click();
        cy.wait(500) // Pausa para ver el mensaje
        cy.get('#confirm-message').should('contain', '¿Hacer este reporte público?');
        cy.get('#confirm-ok').click();
        cy.visit(`${baseUrl}/crud`)
        cy.wait(2000)

        //6. ir a la pantalla de reportes publicos
        cy.visit(`${baseUrl}/crud`)
        
        // Opcional: Verificar que la tabla de contactos sea visible
        cy.get('body').should('contain', 'Contactos') 
    })
})