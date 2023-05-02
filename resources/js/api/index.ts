export type Method = "GET" | "POST" | "PUT" | "PATCH" | "DELETE";

export interface Transaction {
    remoteId: string;
    status: string;
    rawText: string;
    description: string;
    message: string;
    amount: MoneyObject;
    settledAt: string;
    createdAt: string;
}

export interface MoneyObject {
    currencyCode: string;
    value: string;
    baseUnitValue: number;
}

export interface Account {
    remoteId: string;
    displayName: string;
    type: string;
    ownershipType: string;
    balance: MoneyObject;
    createdAt: string;
}

export interface Category {
    remoteId: string;
    name: string;
    parentId: string;
}

export default class ApiService {

    
    public apiUrl = '/api/v1'; // Change this to the base URL of your Laravel API

    convertQueryParams(params?: Record<string, any>) {
        return new URLSearchParams(params).toString();
    } 

    async sendRequest(endpoint: string, method: Method, params?: Record<string, any>, data?: any): Promise<any> {

        const queryParams = new URLSearchParams(params).toString();

        const response = await fetch(`${this.apiUrl}/${endpoint}?${queryParams}`, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            credentials: 'same-origin', // Ensure cookies are sent with the request
            body: JSON.stringify(data),
        });

        if (!response.ok) {
            throw new Error(`API error: ${response.status}`);
        }

        return response.json();
    }

    makeSwrvFetcher() {
        const _this = this;

        return function (url: RequestInfo | URL, params?: Record<string, any>) {
            
            const queryParams = new URLSearchParams(params).toString();

            return fetch(`${_this.apiUrl}/${url}?${queryParams}`).then(r => r.json())
        }
    }
}

