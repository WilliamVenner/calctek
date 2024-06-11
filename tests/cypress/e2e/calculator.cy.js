/// <reference types="cypress" />

context('Calculator', () => {
    beforeEach(() => {
        cy.visit('/');
    });

    it('should add + to the input field when clicking the addition operator button', () => {
        cy.get('#calc-btn-plus').click();
        cy.get('#calc-input-field').should('have.value', '+');
    })
})
