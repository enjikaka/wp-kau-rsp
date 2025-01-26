const html = String.raw;

customElements.define('research-project-card', class extends HTMLElement {
    connectedCallback () {
        this.sDOM = this.attachShadow({
            mode: 'closed'
        });
        this.sDOM.innerHTML = html`
        <style>
        @import url('https://fonts.cdnfonts.com/css/univers-lt-std');
        :host {
            font-family: 'Univers Next Pro', sans-serif;
            display: block;
            font-size: 16px;
            padding: 1em 0;
            box-sizing: border-box;
            position: relative;
        }

        strong {
            font-family: 'Univers LT Std', sans-serif;
            font-size: 18px;
        }

        ::slotted(p) {
            margin: 0;
        }

        :host small ::slotted(a[slot]) {
            text-decoration: none !important;

            &:hover {
                text-decoration: underline
            }
        }

        #badge {
            margin: 0;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            display: flex;
            place-items: center;

            & span {
                background-color: #F6F6F6;
                padding: .4em .6em .2em;
                border-radius: 6px;
                font-size: 12px;
            }
        }

        :host([research-status="completed"]) #badge span {
            background-color: oklch(0.86 0.18 144);
        }
        </style>
        <small><slot name="department"></slot></small><br>
        <strong><slot name="title"></slot></strong>
        <slot name="excerpt"></slot>
        <figure id="badge"><span></span></figure>
        `;
        this.sDOM.querySelector('#badge span').innerHTML = this.getAttribute('research-status') === 'in_progress' ? 'In Progress' : 'Completed';
    }
});
