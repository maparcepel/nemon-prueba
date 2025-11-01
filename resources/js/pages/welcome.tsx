import CalculationForm from '@/features/calculation/CalculationForm';
import DateDetails from '@/features/DateDetails';
import DateList from '@/features/DateList';
import { DatesProvider } from '@/features/dates/contexts/DatesContext';
import { type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Col, Container, Row } from 'react-bootstrap';

export default function Welcome({
    canRegister = true,
}: {
    canRegister?: boolean;
}) {
    const { auth } = usePage<SharedData>().props;

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
                <h1>Nemon - Calculadora de precios de energía indexados</h1>
                <Row>
                    <Col>
                        <CalculationForm />
                    </Col>
                </Row>
                <DatesProvider>
                    <Row>
                        <Col md={2}>
                            <DateList />
                        </Col>

                        <Col md={10}>
                            <DateDetails />
                        </Col>
                    </Row>
                </DatesProvider>
            </Container>
        </>
    );
}
