import { token } from "../core/User";
import { get } from "svelte/store";

export interface PostData {
    id: number,
    uuid: string,
    date: string,
    content: string,
    liked: boolean,
    likeCount: number,
    commentCount: number
}

export interface CommentData {
    id: number,
    uuid: string,
    content: string,
    date: string
}

export interface UserData {
    id: string,
    following: boolean,
    followerCount: number,
    postCount: number,
}

const base: string = "https://social.schindlerfelix.de/api/";

/**
 * Build the data string
 * x-www-form-urlencoded
 */
function encode(data: any): string {
    let dataStr: string = "";
    for (const [key, value] of Object.entries(data)) {
        if (dataStr != "")
            dataStr += "&";
        dataStr += key + "=" + encodeURIComponent(value.toString())
    }
    return dataStr;
}

/**
 * GET data asynchronous from an endpoint
 *
 * @param   {string}   endpoint  The API endpoint (e. g. "feed")
 * @param   {boolean}  auth      Whether the token should be send or not
 *
 * @return  {any}               Response data as JSON or null if failed
 */
export async function GET(endpoint: string, auth: boolean = true): Promise<any> {
    let response: any;
    if (auth && get(token)) {
        response = await fetch(base + endpoint, {
            headers: {
                'Authorization': get(token)
            }
        });
    } else response = await fetch(base + endpoint)

    return await response.json();
}

/**
 * POST data (json object) to an endpoint
 *
 * @param   {string}   endpoint  The API endpoint (e. g. "login")
 * @param   {any}      data      Data to be send encoded as JSON
 * @param   {boolean}  auth      Whether the token should be send or not
 *
 * @return  {any}                Response data as JSON or null if failed
 */
export async function POST(endpoint: string, data: any, auth: boolean = true): Promise<any> {
    let dataStr: string = "";
    if (data != null)
        dataStr = encode(data);

    var headers: any = null;
    if (auth && get(token)) {
        headers = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'Authorization': get(token)
        }
    } else {
        headers = {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
        }
    }

    // Send the request with correct method, headers and body
    let response = await fetch(base + endpoint, {
        method: 'POST',
        headers: headers,
        body: dataStr
    });

    // Return the result as json
    return await response.json();
}

/**
 * PUT data (json object) to an endpoint
 *
 * @param   {string}   endpoint  The API endpoint (e. g. "login")
 * @param   {any}      data      Data to be send encoded as JSON
 *
 * @return  {any}                Response data as JSON or null if failed
 */
export async function PUT(endpoint: string, data: any): Promise<any> {
    if (!get(token)) return null;

    let dataStr: string = "";
    if (data != null)
        dataStr = encode(data);

    // Send the request with correct method, headers and body
    let response = await fetch(base + endpoint, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'Authorization': get(token)
        },
        body: dataStr
    });

    // Return the result as json
    return await response.json();
}

/**
 * PUT data (json object) to an endpoint
 *
 * @param   {string}   endpoint  The API endpoint (e. g. "login")
 *
 * @return  {any}                Response data as JSON or null if failed
 */
export async function DELETE(endpoint: string): Promise<any> {
    if (!token) return null;

    // Send the request with correct method, headers and body
    let response = await fetch(base + endpoint, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'Authorization': get(token)
        }
    });

    // Return the result as json
    return await response.json();
}
