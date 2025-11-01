import CalculationForm from '@/features/calculation/CalculationForm';
import DateDetails from '@/features/DateDetails';
import DateList from '@/features/DateList';
import { DatesProvider } from '@/features/dates/contexts/DatesContext';
import { Head } from '@inertiajs/react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Col, Container, Row } from 'react-bootstrap';

export default function Welcome() {
    return (
        <>
            <Head title="Nemon">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link
                    href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
                    rel="stylesheet"
                />
            </Head>

            <Container>
                <h1 className="my-4">Nemon</h1>
                <Row>
                    <Col>
                        <CalculationForm />
                    </Col>
                </Row>
                <DatesProvider>
                    <Row className="mb-5">
                        <Col className="mt-4" md={3} lg={2}>
                            <DateList />
                        </Col>

                        <Col className="mt-4" md={9} lg={10}>
                            <DateDetails />
                        </Col>
                    </Row>
                </DatesProvider>
            </Container>
        </>
    );
}
