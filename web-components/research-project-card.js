const html = String.raw;

customElements.define('research-project-card', class extends HTMLElement {
    connectedCallback() {
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
            text-align: justify;
            hyphens: auto;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        :host small ::slotted(a[slot]) {
            text-decoration: none !important;

            &:hover {
                text-decoration: underline
            }
        }

        footer {
            display: flex;
            flex-flow: row wrap;
            align-items: center;
            gap: .5rem;
        }

        #badge {
            margin: 0;

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
        <footer>
            <figure id="badge"><span></span></figure>
            <small><slot name="researchers"></slot></small>
        </footer>
        `;
        this.sDOM.querySelector('#badge span').innerHTML = this.getAttribute('research-status') === 'in_progress' ? 'Pågående' : 'Avslutad';
    }
});
