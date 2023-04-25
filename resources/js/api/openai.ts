import { Configuration, OpenAIApi } from "openai";

export default class OpenAiClient {
    private configuration: Configuration;
    private openai: OpenAIApi;

    constructor() {
        this.configuration = new Configuration({
            apiKey: import.meta.env.VITE_OPEN_API_KEY,
        });
        this.openai = new OpenAIApi(this.configuration);
    }

    async sendRequest() {
        if (!this.configuration.apiKey) {
            throw new Error("OpenAI API key not configured, please follow instructions in README.md");
        }

        try {
            const completion = await this.openai.createCompletion({
                model: "text-davinci-003",
                prompt: `Suggest three names for an animal that is a superhero.
          Animal: Cat
          Names: Captain Sharpclaw, Agent Fluffball, The Incredible Feline
          Animal: Dog
          Names: Ruff the Protector, Wonder Canine, Sir Barks-a-Lot
          Animal: giraffe
          Names:`,
                temperature: 0.6,
            });

            return completion.data.choices[0].text
        } catch (error: any) {
            // Consider adjusting the error handling logic for your use case
            if (error.response) {
                console.error(error.response.status, error.response.data);
                return error.response.data;
            } else {
                console.error(`Error with OpenAI API request: ${error.message}`);
            }
        }
    }
}
