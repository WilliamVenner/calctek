/// <reference types="cypress" />

context('Calculator', () => {
    beforeEach(() => {
        cy.visit('/');
    });

    [
        ['#calc-btn-0', '0'],
        ['#calc-btn-1', '1'],
        ['#calc-btn-2', '2'],
        ['#calc-btn-3', '3'],
        ['#calc-btn-4', '4'],
        ['#calc-btn-5', '5'],
        ['#calc-btn-6', '6'],
        ['#calc-btn-7', '7'],
        ['#calc-btn-8', '8'],
        ['#calc-btn-9', '9'],
        ['#calc-btn-decimal', '.'],
        ['#calc-btn-plus', '+'],
        ['#calc-btn-minus', '-'],
        ['#calc-btn-mul', '⨯'],
        ['#calc-btn-div', '÷'],
        ['#calc-btn-pow', '^'],
        ['#calc-btn-percent', '%'],
        ['#calc-btn-open-p', '('],
        ['#calc-btn-close-p', ')'],
    ].forEach(([btnId, expectedResult]) => {
        it(`should add ${expectedResult} to the input field when clicking the respective operator button`, () => {
            cy.get(btnId).click();
            cy.get('#calc-input-field').should('have.value', expectedResult);
        });
    });

    it('clear button should clear the input field', () => {
        cy.get('#calc-btn-1').click();
        cy.get('#calc-btn-2').click();
        cy.get('#calc-btn-3').click();
        cy.get('#calc-input-field').should('have.value', '123');

        cy.get('#calc-btn-clear').click();
        cy.get('#calc-input-field').should('have.value', '');
    });

    it('backspace button should remove one character from the end of the input field', () => {
        cy.get('#calc-btn-1').click();
        cy.get('#calc-btn-2').click();
        cy.get('#calc-btn-3').click();
        cy.get('#calc-input-field').should('have.value', '123');

        cy.get('#calc-btn-backsp').click();
        cy.get('#calc-input-field').should('have.value', '12');
    });

    it('calculator should display result of complex calculation', () => {
        const fiveFactorial = 120; // 5!
        const tenPercent = 0.1; // 10%
        const expected = Math.round(Math.max((1 + 2 * 3 ** 4 / fiveFactorial), ((6 + 7 * 8 ** ((9 * tenPercent) % fiveFactorial)) / 2) ** 3)).toString();

        cy.intercept('/calc/eval/*').as('calcEval');

        cy.get('#calc-input-field').type('round(max((1+2*3^4/5!), ((6+7*8^mod(9*10%,5!))/2)^3))');
        cy.get('#calc-btn-equals').click();
        cy.wait('@calcEval');
        cy.get('#calc-input-field').should('have.value', expected);
    });

    it('invalid expressions should be rejected and this fact should be displayed to the user', () => {
        cy.intercept('/calc/eval/*').as('calcEval');

        cy.get('#calc-input').should('not.have.class', 'error');
        cy.get('#calc-input-field').type('blah()');
        cy.get('#calc-btn-equals').click();
        cy.wait('@calcEval');
        cy.get('#calc-input').should('have.class', 'error');
    });

    it('history is added to history sidebar after evaluating an expression', () => {
        cy.intercept('/calc/eval/*').as('calcEval');

        cy.get('#calc-input-field').type('1+2+3');
        cy.get('#calc-btn-equals').click();
        cy.wait('@calcEval');
        cy.get('#calc-input-field').should('have.value', '6');

        cy.get('#calc-btn-history').click();

        cy.get('#calc-history-entries > *:first-child > .calc-history-entry-input').should('contain.text', '1+2+3');
        cy.get('#calc-history-entries > *:first-child > .calc-history-entry-output').should('contain.text', '6');

        it('clicking a history entry sets the input field\'s value to the result of the history entry\'s input expression', () => {
            cy.get('#calc-input-field').type('3-2-1');
            cy.get('#calc-btn-equals').click();
            cy.wait('@calcEval');
            cy.get('#calc-input-field').should('have.value', '0');

            cy.get('#calc-history-entries > *:last-child').click();
            cy.get('#calc-input-field').should('have.value', '6');
        });
    });

    it('landscape history sidebar visibility is remembered', () => {
        cy.clearLocalStorage();
        cy.viewport(1000, 660);
        cy.visit('/');
        cy.get('#calc-btn-history').click();
        cy.visit('/');
        cy.get('#calc-history-sidebar').should('be.visible');
    });

    it('portrait -> open history sidebar -> landscape should not show history sidebar', () => {
        cy.clearLocalStorage();
        cy.viewport(660, 1000);
        cy.visit('/');
        cy.get('#calc-history-sidebar').should('not.be.visible');
        cy.get('#calc-btn-history').click();
        cy.get('#calc-history-sidebar').should('be.visible');
        cy.viewport(1000, 660);
        cy.get('#calc-history-sidebar').should('not.be.visible');

        it('portrait -> open history sidebar -> landscape -> portrait should not show history sidebar', () => {
            cy.viewport(660, 1000);
            cy.get('#calc-history-sidebar').should('not.be.visible');
        });
    });

    it('landscape -> open history sidebar -> portrait should not show history sidebar', () => {
        cy.viewport(1000, 660);
        cy.get('#calc-btn-history').click();
        cy.get('#calc-history-sidebar').should('be.visible');
        cy.viewport(660, 1000);
        cy.get('#calc-history-sidebar').should('not.be.visible');

        it('landscape -> open history sidebar -> portrait -> landscape should show history sidebar', () => {
            cy.viewport(1000, 660);
            cy.get('#calc-history-sidebar').should('be.visible');
        });
    });

    it('portrait -> open history sidebar -> clicking background should hide history sidebar', () => {
        cy.viewport(660, 1000);
        cy.get('#calc-btn-history').click();
        cy.get('#calc-history-sidebar').should('be.visible');
        cy.get('#calc-history-sidebar + div').click();
        cy.get('#calc-history-sidebar').should('not.be.visible');
    });
});
