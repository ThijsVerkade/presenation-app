// from: https://gist.github.com/ghinda/8442a57f22099bdb2e34
export const prepareForm = (obj: Record<string, any>, form?: FormData, namespace?: string): FormData => {
    const fd = form || new FormData();
    let formKey: string;

    for (const property in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, property)) {
            formKey = namespace ? `${namespace}[${property}]` : property;

            // Handle arrays
            if (Array.isArray(obj[property])) {
                obj[property].forEach((element: any, index: number) => {
                    if (typeof element === 'object' && !(element instanceof File)) {
                        prepareForm(element, fd, `${formKey}[${index}]`);
                    } else {
                        fd.append(`${formKey}[${index}]`, element);
                    }
                });
            }
            // Handle objects (non-arrays)
            else if (typeof obj[property] === 'object' && !(obj[property] instanceof File)) {
                prepareForm(obj[property], fd, formKey);
            } else {
                fd.append(formKey, obj[property]);
            }
        }
    }

    return fd;
};
